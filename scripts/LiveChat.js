    function openLiveChatModal(chatUrl) {
        var overlay = document.getElementById('livechatSidebarOverlay');
        var iframe = document.getElementById('livechatIframe');
        var newSrc = chatUrl ? chatUrl : 'messages.php';
        if (iframe.src !== newSrc && iframe.contentWindow.location.href !== newSrc) {
            iframe.src = newSrc;
        }
        overlay.style.display = 'block';
        setTimeout(function(){
            var closeBtn = document.querySelector('#livechatSidebarWrapper button');
            if(closeBtn) closeBtn.focus();
        }, 100);
    }
function closeChatSidebar() {
    var overlay = document.getElementById('livechatSidebarOverlay');
    if (overlay.contains(document.activeElement)) document.activeElement.blur();
    overlay.style.display = 'none';
}
window.addEventListener('message', function(event) {
    if(event.origin !== window.location.origin) return;
    if(event.data && event.data.type === 'openChat' && event.data.url) {
        openLiveChatModal(event.data.url);
    }
});
let lastUnreadCount = 0;
if ("Notification" in window && Notification.permission !== "granted") {
Notification.requestPermission();
}
function showChatNotification(title, body) {
    if ("Notification" in window && Notification.permission === "granted") {
        new Notification(title, { body: body, icon: '../img/DRTS_logo.png' });
    }
}
setInterval(fetchUnreadCount, 10000);
document.addEventListener('DOMContentLoaded', fetchUnreadCount);
function fetchUnreadCount() {
    fetch('api/unread_count.php')
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('chatBadge');
            if (data.unread > 0) {
                badge.textContent = data.unread;
                badge.style.display = 'inline-block';
                if (data.unread > lastUnreadCount) {
                    playNotificationSound();
                    showChatNotification('New Chat Message', 'You have a new message!');
                }
            } else {
                badge.style.display = 'none';
            } 
            lastUnreadCount = data.unread;
        });
}
function playNotificationSound() {
    const audio = document.getElementById('chatNotificationSound');
    if (audio) {
        audio.currentTime = 0;
        audio.play();
    }
}
window.addEventListener('message', function(event) {
    if(event.origin !== window.location.origin) return;
    if(event.data && event.data.type === 'chatNotification') {
        playNotificationSound();
    }
});
