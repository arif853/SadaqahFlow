import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import Sidebar from './Sidebar';
import Header from './Header';
import Toast from '../Components/Toast';

export default function AdminLayout({ children, title }) {
    const { flash } = usePage().props;

    return (
        <>
            {/* Loader */}
            <div className="codex-loader" style={{ display: 'none' }}>
                <div className="linespinner"></div>
            </div>

            {/* Header */}
            <Header />

            {/* Sidebar */}
            <Sidebar />

            {/* Main Content */}
            <div className="themebody-wrap">
                {children}
            </div>

            {/* Footer */}
            <footer className="codex-footer">
                <p>Copyright 2024-2025 © CPDS-DK-BS, All rights reserved.</p>
            </footer>

            {/* Back to top */}
            <div className="scroll-top"><i className="fa fa-angle-double-up"></i></div>

            {/* Toast notifications */}
            <Toast flash={flash} />
        </>
    );
}
