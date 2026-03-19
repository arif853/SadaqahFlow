import React from 'react';
import { Link, router, usePage } from '@inertiajs/react';

export default function Header() {
    const { auth } = usePage().props;
    const user = auth?.user;

    const handleLogout = (e) => {
        e.preventDefault();
        router.post('/logout');
    };

    return (
        <header className="codex-header">
            <div className="header-contian d-flex justify-content-between align-items-center">
                <div className="header-left d-flex align-items-center">
                    <div className="sidebar-action navicon-wrap">
                        <i data-feather="menu"></i>
                    </div>
                    <div className="search-bar">
                        <div className="form-group mb-0">
                            <div className="input-group">
                                <input className="form-control" type="text" placeholder="Search Here....." />
                                <span className="input-group-text"><i data-feather="search"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="header-right d-flex align-items-center justify-content-end">
                    <ul className="nav-iconlist">
                        <li className="nav-profile">
                            <div className="media">
                                <div className="user-icon">
                                    <img className="img-fluid rounded-50" src="/assets/images/avtar/3.jpg" alt="avatar" />
                                </div>
                                <div className="media-body d-block">
                                    <h6>{user?.name}</h6>
                                    <span className="text-light">{user?.roles?.[0]}</span>
                                </div>
                            </div>
                            <div className="hover-dropdown navprofile-drop">
                                <ul>
                                    <li><Link href="/profile"><i className="ti-settings"></i>setting</Link></li>
                                    <li>
                                        <button type="button" className="border-0 bg-transparent p-0" onClick={handleLogout}>
                                            <i className="fa fa-sign-out"></i>log out
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
    );
}
