import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    enabledTransports: ["ws", "wss"],
});

/**
 * Pages kept in the back/forward cache keep their WebSocket open, which leaves
 * the user in presence channels after navigating away or logging out. Close the
 * connection when the page is hidden and reconnect (pusher-js re-subscribes
 * every channel) if the page is restored from that cache.
 */
window.addEventListener('pagehide', () => window.Echo.connector.pusher.disconnect());

window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        window.Echo.connector.pusher.connect();
    }
});
