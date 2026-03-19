import React, { useState, useEffect } from 'react';
import { Head, usePage } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';

export default function UserWiseReport({ users, programs }) {
    const { auth } = usePage().props;
    const roles = auth?.user?.roles || [];
    const isAdmin = roles.includes('Super Admin') || roles.includes('Admin');

    const [userId, setUserId] = useState('');
    const [programId, setProgramId] = useState('');
    const [date, setDate] = useState('');
    const [name, setName] = useState('');
    const [preview, setPreview] = useState(null);

    const handlePreview = async () => {
        const params = new URLSearchParams();
        if (userId) params.append('userid', userId);
        if (programId) params.append('programId', programId);
        if (date) params.append('date', date);
        if (name) params.append('name', name);
        const res = await fetch(`/reports/user-wise-report/fetchKhedmot?${params}`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        setPreview(data.khedmots || []);
    };

    const handleDownload = () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/reports/user-wise-report/fetchKhedmot';
        form.target = '_blank';
        const csrf = document.createElement('input');
        csrf.type = 'hidden'; csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrf);
        if (userId) { const i = document.createElement('input'); i.type='hidden'; i.name='userId'; i.value=userId; form.appendChild(i); }
        if (programId) { const i = document.createElement('input'); i.type='hidden'; i.name='programId'; i.value=programId; form.appendChild(i); }
        if (date) { const i = document.createElement('input'); i.type='hidden'; i.name='date'; i.value=date; form.appendChild(i); }
        if (name) { const i = document.createElement('input'); i.type='hidden'; i.name='name'; i.value=name; form.appendChild(i); }
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    };

    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    return (
        <AdminLayout>
            <Head title="কর্মী রিপোর্ট" />
            <Breadcrumb title="রিপোর্ট" items={[{ label: 'কর্মী রিপোর্ট', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header"><h4>কর্মী রিপোর্ট</h4></div>
                        <div className="card-body">
                            <div className="row g-2 mb-3">
                                {isAdmin && (
                                    <div className="col-md-3">
                                        <label className="form-label">কর্মী</label>
                                        <select className="form-control" value={userId} onChange={e => setUserId(e.target.value)}>
                                            <option value="">সবাই</option>
                                            {(users || []).map(u => <option key={u.id} value={u.id}>{u.name}</option>)}
                                        </select>
                                    </div>
                                )}
                                <div className="col-md-3">
                                    <label className="form-label">অনুষ্ঠান</label>
                                    <select className="form-control" value={programId} onChange={e => setProgramId(e.target.value)}>
                                        <option value="">নির্বাচন করুন</option>
                                        {(programs || []).map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                                    </select>
                                </div>
                                <div className="col-md-2">
                                    <label className="form-label">তারিখ</label>
                                    <input type="date" className="form-control" value={date} onChange={e => setDate(e.target.value)} />
                                </div>
                                <div className="col-md-2">
                                    <label className="form-label">নাম/কল্যাণ নং</label>
                                    <input type="text" className="form-control" value={name} onChange={e => setName(e.target.value)} />
                                </div>
                                <div className="col-md-2 d-flex align-items-end gap-2">
                                    <button className="btn btn-info" onClick={handlePreview}>প্রিভিউ</button>
                                    <button className="btn btn-success" onClick={handleDownload}>PDF</button>
                                </div>
                            </div>

                            {preview && (
                                <div className="table-responsive">
                                    <table className="table">
                                        <thead>
                                            <tr><th>নং</th><th>জাকের</th><th>তারিখ</th><th>খেদমত</th><th>মানত</th><th>কল্যাণ</th><th>ভাড়া</th><th>কর্মী</th></tr>
                                        </thead>
                                        <tbody>
                                            {preview.map((k, idx) => (
                                                <tr key={k.id}>
                                                    <td>{idx + 1}</td>
                                                    <td>{k.member?.name}</td>
                                                    <td>{k.date}</td>
                                                    <td>{k.khedmot_amount || 0}</td>
                                                    <td>{k.manat_amount || 0}</td>
                                                    <td>{k.kalyan_amount || 0}</td>
                                                    <td>{k.rent_amount || 0}</td>
                                                    <td>{k.user?.name}</td>
                                                </tr>
                                            ))}
                                            <tr className="fw-bold">
                                                <td colSpan="3">মোট</td>
                                                <td>{preview.reduce((s, k) => s + (parseFloat(k.khedmot_amount) || 0), 0)}</td>
                                                <td>{preview.reduce((s, k) => s + (parseFloat(k.manat_amount) || 0), 0)}</td>
                                                <td>{preview.reduce((s, k) => s + (parseFloat(k.kalyan_amount) || 0), 0)}</td>
                                                <td>{preview.reduce((s, k) => s + (parseFloat(k.rent_amount) || 0), 0)}</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
