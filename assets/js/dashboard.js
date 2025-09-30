// assets/js/dashboard.js
(() => {
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifBadge = document.getElementById('notifBadge');
    const notifList = document.getElementById('notifList');
    const markAllReadBtn = document.getElementById('markAllRead');
  
    const modalRoot = document.getElementById('modalRoot');
    const closeModalBtn = document.getElementById('closeModal');
    const chatList = document.getElementById('chatList');
    const modalTicketNum = document.getElementById('modalTicketNum');
    const replyForm = document.getElementById('replyForm');
    const replyMessage = document.getElementById('replyMessage');
    const replyTicketIdInput = document.getElementById('replyTicketId');
    const sendReplyBtn = document.getElementById('sendReplyBtn');
  
    let activeTicketId = null;
    let pollingNotifs = null;
    let pollingChat = null;
  
    /* ================== NOTIFIKASI ================== */
  
    async function fetchNotifs() {
      try {
        const res = await fetch('../actions/fetch_notifications.php', { cache: 'no-store' });
        if (!res.ok) return;
        const data = await res.json();
  
        // Badge
        if (data.unread > 0) {
          notifBadge.textContent = data.unread;
          notifBadge.classList.remove('hidden');
        } else {
          notifBadge.classList.add('hidden');
        }
  
        // List
        notifList.innerHTML = '';
        if (!data.items || data.items.length === 0) {
          notifList.innerHTML = '<div class="p-3 text-sm text-gray-500">Belum ada notifikasi</div>';
        } else {
          data.items.forEach(n => {
            const a = document.createElement('a');
            a.href = n.ticket_id ? ('ticket_view.php?id=' + n.ticket_id) : '#';
            a.className = 'block px-3 py-2 hover:bg-gray-50 border-b text-sm';
            const time = new Date(n.created_at).toLocaleString();
            a.innerHTML = `<div class="font-medium">${n.actor_name ? n.actor_name + ' • ' : ''}<span class="text-gray-500 text-xs float-right">${time}</span></div>
                           <div class="text-gray-700">${n.message}</div>`;
            notifList.appendChild(a);
          });
        }
      } catch (e) {
        console.error(e);
      }
    }
  
    async function markAllRead() {
      try {
        await fetch('../actions/mark_notifications_read.php', { method: 'POST' });
        fetchNotifs();
      } catch (e) {
        console.error(e);
      }
    }
  
    notifBtn?.addEventListener('click', () => {
      notifDropdown.classList.toggle('hidden');
      if (!notifDropdown.classList.contains('hidden')) {
        markAllRead();
      }
    });
  
    markAllReadBtn?.addEventListener('click', markAllRead);
  
    fetchNotifs();
    pollingNotifs = setInterval(fetchNotifs, 5000);
  
    /* ================== CHAT MODAL ================== */
  
    function openModal(ticketId, ticketNumber) {
      activeTicketId = ticketId;
      replyTicketIdInput.value = ticketId;
      modalTicketNum.textContent = ticketNumber || '';
      modalRoot.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      loadChat();
      pollingChat = setInterval(loadChat, 3000);
    }
  
    function closeModal() {
      activeTicketId = null;
      replyTicketIdInput.value = '';
      replyMessage.value = '';
      modalRoot.classList.add('hidden');
      document.body.style.overflow = '';
      clearInterval(pollingChat);
      chatList.innerHTML = '';
    }
  
    async function loadChat() {
      if (!activeTicketId) return;
      try {
        const res = await fetch(`../actions/replies.php?ticket_id=${activeTicketId}`, { cache: 'no-store' });
        if (!res.ok) return;
        const data = await res.json();
        chatList.innerHTML = '';
        if (!Array.isArray(data) || data.length === 0) {
          chatList.innerHTML = '<div class="text-sm text-gray-500">Belum ada balasan.</div>';
          return;
        }
        data.forEach(msg => {
          const wrap = document.createElement('div');
          wrap.className = 'p-2 my-1 rounded ' + (msg.is_self ? 'bg-blue-100 text-right' : 'bg-gray-100 text-left');
          wrap.innerHTML = `
            <div class="text-xs text-gray-600 mb-1">${msg.username} • ${new Date(msg.created_at).toLocaleString()}</div>
            <div class="bg-white p-2 rounded shadow-sm">${escapeHtml(msg.message).replace(/\n/g, '<br>')}</div>
          `;
          chatList.appendChild(wrap);
        });
        chatList.scrollTop = chatList.scrollHeight;
      } catch (e) {
        console.error(e);
      }
    }
  
    async function sendReply() {
      const msg = replyMessage.value.trim();
      if (!msg || !activeTicketId) return;
      sendReplyBtn.disabled = true;
      try {
        const form = new FormData();
        form.append('ticket_id', activeTicketId);
        form.append('message', msg);
        const res = await fetch('../actions/replies.php', { method: 'POST', body: form });
        const data = await res.json();
        if (data.ok) {
          replyMessage.value = '';
          loadChat();
          fetchNotifs();
        } else {
          alert(data.error || 'Gagal mengirim balasan');
        }
      } catch (e) {
        console.error(e);
      } finally {
        sendReplyBtn.disabled = false;
      }
    }
  
    function escapeHtml(str) {
      return str.replace(/[&<>"'`=\/]/g, s => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;',
        "'": '&#39;', '/': '&#x2F;', '`': '&#x60;', '=': '&#x3D;'
      }[s]));
    }
  
    document.querySelectorAll('.open-chat-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const tid = btn.getAttribute('data-ticket-id');
        const tnum = btn.getAttribute('data-ticket-number');
        openModal(tid, tnum);
      });
    });
  
    closeModalBtn?.addEventListener('click', closeModal);
    modalRoot?.addEventListener('click', (e) => {
      if (e.target.classList.contains('modal-backdrop')) closeModal();
    });
  
    sendReplyBtn?.addEventListener('click', sendReply);
    replyMessage?.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) sendReply();
    });
  
    /* ================== SEARCH & SORT ================== */
  
    const searchInput = document.getElementById('searchInput');
    const ticketTable = document.getElementById('ticketTable');
    const rows = ticketTable?.querySelectorAll('tbody tr');
  
    // Search
    searchInput?.addEventListener('input', () => {
      const q = searchInput.value.toLowerCase();
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
      });
    });
  
    // Sort
    document.querySelectorAll('#ticketTable thead th.sortable').forEach((th, index) => {
      th.addEventListener('click', () => {
        const tbody = ticketTable.querySelector('tbody');
        const sorted = [...tbody.rows].sort((a, b) => {
          const aText = a.cells[index].textContent.trim().toLowerCase();
          const bText = b.cells[index].textContent.trim().toLowerCase();
          return aText.localeCompare(bText);
        });
        tbody.innerHTML = '';
        sorted.forEach(r => tbody.appendChild(r));
      });
    });
    
  
  })();
  