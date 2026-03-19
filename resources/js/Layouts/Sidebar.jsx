import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function Sidebar() {
    const { auth, appName } = usePage().props;
    const permissions = auth?.user?.permissions || [];
    const roles = auth?.user?.roles || [];

    const can = (permission) => hasAnyRole('Super Admin') || permissions.includes(permission);
    const canAny = (perms) => hasAnyRole('Super Admin') || perms.some(p => permissions.includes(p));
    const hasAnyRole = (...roleNames) => roleNames.some(r => roles.includes(r));

    const [openMenu, setOpenMenu] = useState('');
    const toggleMenu = (menu) => {
        setOpenMenu(openMenu === menu ? '' : menu);
    };

    return (
        <aside className="codex-sidebar">
            <div className="logo-gridwrap">
                <Link className="codexbrand-logo" href="/dashboard">
                    <h2>{appName} <sub className="text-danger font-weight-bold" style={{ fontSize: '11px' }}>v1.0</sub></h2>
                </Link>
                <div className="sidebar-action"><i data-feather="menu"></i></div>
            </div>
            <div className="icon-logo">
                <Link href="/dashboard"></Link>
            </div>
            <div className="codex-menuwrapper">
                <ul className="codex-menu custom-scroll" data-simplebar>
                    <li className="cdxmenu-title"><h5>হোম</h5></li>
                    <li className="menu-item">
                        <Link href="/dashboard">
                            <div className="icon-item"><i data-feather="home"></i></div>
                            <span>ড্যাশবোর্ড</span>
                        </Link>
                    </li>

                    <li className="cdxmenu-title"><h5>অ্যাপ্লিকেশান</h5></li>

                    {can('view member') && (
                        <li className="menu-item">
                            <Link href="/members">
                                <div className="icon-item"><i data-feather="users"></i></div>
                                <span>জাকের</span>
                            </Link>
                        </li>
                    )}

                    {canAny(['view khedmot', 'view rent', 'view kollyan']) && (
                        <li className={`menu-item ${openMenu === 'khedmot' ? 'active' : ''}`}>
                            <a href="#" onClick={(e) => { e.preventDefault(); toggleMenu('khedmot'); }}>
                                <div className="icon-item"><i data-feather="message-square"></i></div>
                                <span>খেদমত সংগ্রহ</span>
                                <i className="fa fa-angle-down"></i>
                            </a>
                            <ul className="submenu-list" style={{ display: openMenu === 'khedmot' ? 'block' : 'none' }}>
                                {can('view khedmot') && <li><Link href="/khedmots">খেদমত/মানত</Link></li>}
                                {can('view rent') && <li><span className="d-inline-block py-1 px-0 text-muted">ভাড়া (শীঘ্রই)</span></li>}
                                {can('view kollyan') && <li><span className="d-inline-block py-1 px-0 text-muted">কল্যাণ (শীঘ্রই)</span></li>}
                            </ul>
                        </li>
                    )}

                    {can('view fund-collection') && (
                        <li className={`menu-item ${openMenu === 'fund-collection' ? 'active' : ''}`}>
                            <a href="#" onClick={(e) => { e.preventDefault(); toggleMenu('fund-collection'); }}>
                                <div className="icon-item"><i data-feather="dollar-sign"></i></div>
                                <span>খেদমত লেনদেন</span>
                                <i className="fa fa-angle-down"></i>
                            </a>
                            <ul className="submenu-list" style={{ display: openMenu === 'fund-collection' ? 'block' : 'none' }}>
                                <li><Link href="/fund_collections/receive/index">খেদমত জমা</Link></li>
                                {hasAnyRole('Super Admin', 'Admin') && (
                                    <li><Link href="/fund_collections/pay/index">খেদমত খরচ</Link></li>
                                )}
                            </ul>
                        </li>
                    )}

                    {can('view user') && (
                        <li className={`menu-item ${openMenu === 'user' ? 'active' : ''}`}>
                            <a href="#" onClick={(e) => { e.preventDefault(); toggleMenu('user'); }}>
                                <div className="icon-item"><i data-feather="user"></i></div>
                                <span>ম্যানেজ ইউজার</span>
                                <i className="fa fa-angle-down"></i>
                            </a>
                            <ul className="submenu-list" style={{ display: openMenu === 'user' ? 'block' : 'none' }}>
                                {can('view user') && <li><Link href="/users">ইউজার'স</Link></li>}
                                {can('view role') && <li><Link href="/dashboard/users/roles">রোল'স</Link></li>}
                                {can('view permission') && <li><Link href="/dashboard/users/permissions">পারমিশন</Link></li>}
                            </ul>
                        </li>
                    )}

                    {can('view setting') && (
                        <li className={`menu-item ${openMenu === 'setting' ? 'active' : ''}`}>
                            <a href="#" onClick={(e) => { e.preventDefault(); toggleMenu('setting'); }}>
                                <div className="icon-item"><i data-feather="user"></i></div>
                                <span>সেটিং</span>
                                <i className="fa fa-angle-down"></i>
                            </a>
                            <ul className="submenu-list" style={{ display: openMenu === 'setting' ? 'block' : 'none' }}>
                                {can('view program') && <li><Link href="/dashboard/program-types">অনুষ্ঠান ধরন</Link></li>}
                            </ul>
                        </li>
                    )}

                    {can('view report') && (
                        <li className={`menu-item ${openMenu === 'report' ? 'active' : ''}`}>
                            <a href="#" onClick={(e) => { e.preventDefault(); toggleMenu('report'); }}>
                                <div className="icon-item"><i data-feather="user"></i></div>
                                <span>রিপোর্টস</span>
                                <i className="fa fa-angle-down"></i>
                            </a>
                            <ul className="submenu-list" style={{ display: openMenu === 'report' ? 'block' : 'none' }}>
                                <li><Link href="/reports/user-wise-report">কর্মী রিপোর্ট</Link></li>
                            </ul>
                        </li>
                    )}
                </ul>
            </div>
        </aside>
    );
}
