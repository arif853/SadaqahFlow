import React, { useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';

export default function ReceiveCreate({ khedmots, members }) {
    const [selected, setSelected] = useState([]);
    const form = useForm({
        date: '', khedmot_id: '', khedmot_amount: 0, manat_amount: 0,
        kollan_amount: 0, rent_amount: 0, total_amount: 0, comment: ''
    });

    const toggleSelect = (id) => {
        setSelected(prev => prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]);
    };

    useEffect(() => {
        const sel = (khedmots || []).filter(k => selected.includes(k.id));
        const khedmotAmt = sel.reduce((sum, k) => sum + (parseFloat(k.khedmot_amount) || 0), 0);
        const manatAmt = sel.reduce((sum, k) => sum + (parseFloat(k.manat_amount) || 0), 0);
        const kalyanAmt = sel.reduce((sum, k) => sum + (parseFloat(k.kalyan_amount) || 0), 0);
        const rentAmt = sel.reduce((sum, k) => sum + (parseFloat(k.rent_amount) || 0), 0);
        form.setData(prev => ({
            ...prev,
            khedmot_id: selected.join(','),
            khedmot_amount: khedmotAmt, manat_amount: manatAmt,
            kollan_amount: kalyanAmt, rent_amount: rentAmt,
            total_amount: khedmotAmt + manatAmt + kalyanAmt + rentAmt
        }));
    }, [selected]);

    const handleSubmit = (e) => {
        e.preventDefault();
        form.post('/fund/receive/store');
    };

    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    return (
        <AdminLayout>
            <Head title="নতুন খেদমত জমা" />
            <Breadcrumb title="নতুন খেদমত জমা" items={[{ label: 'ফর্ম', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header"><h4>খেদমত নির্বাচন করুন</h4></div>
                        <div className="card-body">
                            <div className="table-responsive mb-3">
                                <table className="table">
                                    <thead>
                                        <tr><th>✓</th><th>জাকের</th><th>তারিখ</th><th>খেদমত</th><th>মানত</th><th>কল্যাণ</th><th>ভাড়া</th></tr>
                                    </thead>
                                    <tbody>
                                        {(khedmots || []).map(k => (
                                            <tr key={k.id}>
                                                <td><input type="checkbox" checked={selected.includes(k.id)} onChange={() => toggleSelect(k.id)} /></td>
                                                <td>{k.member?.name || ''}</td>
                                                <td>{k.date}</td>
                                                <td>{k.khedmot_amount || 0}</td>
                                                <td>{k.manat_amount || 0}</td>
                                                <td>{k.kalyan_amount || 0}</td>
                                                <td>{k.rent_amount || 0}</td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>

                            <form onSubmit={handleSubmit}>
                                <div className="row">
                                    <div className="col-md-4 mb-3">
                                        <label className="form-label">তারিখ</label>
                                        <input type="date" className="form-control" value={form.data.date} onChange={e => form.setData('date', e.target.value)} required />
                                    </div>
                                    <div className="col-md-4 mb-3">
                                        <label className="form-label">মোট খেদমত</label>
                                        <input type="number" className="form-control" value={form.data.khedmot_amount} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-3">
                                        <label className="form-label">মোট মানত</label>
                                        <input type="number" className="form-control" value={form.data.manat_amount} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-3">
                                        <label className="form-label">মোট কল্যাণ</label>
                                        <input type="number" className="form-control" value={form.data.kollan_amount} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-3">
                                        <label className="form-label">মোট ভাড়া</label>
                                        <input type="number" className="form-control" value={form.data.rent_amount} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-3">
                                        <label className="form-label">সর্বমোট</label>
                                        <input type="number" className="form-control" value={form.data.total_amount} readOnly />
                                    </div>
                                    <div className="col-12 mb-3">
                                        <label className="form-label">মন্তব্য</label>
                                        <textarea className="form-control" value={form.data.comment} onChange={e => form.setData('comment', e.target.value)} />
                                    </div>
                                </div>
                                <button type="submit" className="btn btn-primary" disabled={form.processing || selected.length === 0}>জমা দিন</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
