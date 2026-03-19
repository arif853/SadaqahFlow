import React from 'react';
import { Link } from '@inertiajs/react';

export default function Breadcrumb({ title, items = [] }) {
    return (
        <div className="codex-breadcrumb">
            <div className="breadcrumb-contain">
                <div className="left-breadcrumb">
                    <ul className="breadcrumb mb-0">
                        <li className="breadcrumb-item">
                            <Link href="/dashboard"><h1>{title}</h1></Link>
                        </li>
                        {items.map((item, idx) => (
                            <li key={idx} className={`breadcrumb-item ${item.active ? 'active' : ''}`}>
                                {item.href ? <Link href={item.href}>{item.label}</Link> : <span>{item.label}</span>}
                            </li>
                        ))}
                    </ul>
                </div>
                <div className="right-breadcrumb">
                    <ul>
                        <li>
                            <div className="bread-wrap"><i className="fa fa-clock-o"></i></div>
                            <span className="liveTime"></span>
                        </li>
                        <li>
                            <div className="bread-wrap"><i className="fa fa-calendar"></i></div>
                            <span className="getDate"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    );
}
