import { useEffect } from 'react';

export default function Toast({ flash }) {
    useEffect(() => {
        if (flash?.status && typeof window.showNotification === 'function') {
            const type = flash.status.type || 'success';
            const message = flash.status.message || flash.status.success || '';
            if (message) {
                window.showNotification(type, message, type.charAt(0).toUpperCase() + type.slice(1));
            }
        }
    }, [flash]);

    return null;
}
