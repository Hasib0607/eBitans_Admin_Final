export const createSupportRealtimeStream = (url, handlers = {}) => {
    const source = new EventSource(url, {withCredentials: true});

    const parseEvent = (callback) => (event) => {
        try {
            callback?.(JSON.parse(event.data || '{}'));
        } catch (error) {
            // Ignore malformed payloads and let the stream continue.
        }
    };

    source.addEventListener('ready', parseEvent(handlers.onReady));
    source.addEventListener('ping', parseEvent(handlers.onPing));
    source.addEventListener('message', parseEvent(handlers.onMessage));
    source.addEventListener('message_seen', parseEvent(handlers.onMessageSeen));
    source.onerror = (error) => {
        handlers.onError?.(error);
    };

    return source;
};
