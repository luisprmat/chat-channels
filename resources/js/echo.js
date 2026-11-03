import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: "mercure",
    host: import.meta.env.VITE_MERCURE_HUB_URL,
});

/**
 * Pages kept in the back/forward cache keep their WebSocket open, which leaves
 * the user in presence channels after navigating away or logging out. Close the
 * connection when the page is hidden and reconnect (pusher-js re-subscribes
 * every channel) if the page is restored from that cache.
 */
window.addEventListener("pagehide", () =>
    window.Echo.connector.pusher.disconnect(),
);

window.addEventListener("pageshow", (event) => {
    if (event.persisted) {
        window.Echo.connector.pusher.connect();
    }
});
