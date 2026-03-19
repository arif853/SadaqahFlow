import React from 'react';

export default function GuestLayout({ children }) {
    return (
        <div className="codex-signin">
            <div className="signin-contain">
                {children}
            </div>
        </div>
    );
}
