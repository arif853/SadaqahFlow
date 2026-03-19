import React, { useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';

export default function PayCreate({ khedmots, receives }) {
    const form = useForm({
        date: '', pay_to: '', khedmot_amount: '', manat_amount: '',
        kalyan_amount: '', rent_amount: '', total_paid: '', left_amount: '', comment: ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        form.post('/fund_collections/pay/store');
    };

    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    return (
        <AdminLayout>
            <Head title="নতুন খেদমত খরচ" />
            <Breadcrumb title="নতুন খেদমত খরচ" items={[{ label: 'ফর্ম', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header"><h4>খেদমত খরচ ফর্ম</h4></div>
                        <div className="card-body">
                            <form onSubmit={handleSubmit}>
                                <div className="row">
                                    <div className="col-md-6 mb-3">
                                        <label className="form-label">তারিখ *</label>
                                        <input type="date" className="form-control" value={form.data.date} onChange={e => form.setData('date', e.target.value)} required />
                                    </div>
                                    <div className="col-md-6 mb-3">
                                        <label className="form-label">প্রদান করা হবে *</label>
                                        <input type="text" className="form-control" value={form.data.pay_to} onChange={e => form.setData('pay_to', e.target.value)} required />
                                    </div>
                                    <div className="col-md-3 mb-3">
                                        <label className="form-label">খেদমত</label>
                                        <input type="number" className="form-control" value={form.data.khedmot_amount} onChange={e => form.setData('khedmot_amount', e.target.value)} />
                                    </div>
                                    <div className="col-md-3 mb-3">
                                        <label className="form-label">মানত</label>
                                        <input type="number" className="form-control" value={form.data.manat_amount} onChange={e => form.setData('manat_amount', e.target.value)} />
                                    </div>
                                    <div className="col-md-3 mb-3">
                                        <label className="form-label">কল্যাণ</label>
                                        <input type="number" className="form-control" value={form.data.kalyan_amount} onChange={e => form.setData('kalyan_amount', e.target.value)} />
                                    </div>
                                    <div className="col-md-3 mb-3">
                                        <label className="form-label">ভাড়া</label>
                                        <input type="number" className="form-control" value={form.data.rent_amount} onChange={e => form.setData('rent_amount', e.target.value)} />
                                    </div>
                                    <div className="col-md-6 mb-3">
                                        <label className="form-label">মোট প্রদান</label>
                                        <input type="number" className="form-control" value={form.data.total_paid} onChange={e => form.setData('total_paid', e.target.value)} />
                                    </div>
                                    <div className="col-md-6 mb-3">
                                        <label className="form-label">বাকি</label>
                                        <input type="number" className="form-control" value={form.data.left_amount} onChange={e => form.setData('left_amount', e.target.value)} />
                                    </div>
                                    <div className="col-12 mb-3">
                                        <label className="form-label">মন্তব্য</label>
                                        <textarea className="form-control" value={form.data.comment} onChange={e => form.setData('comment', e.target.value)} />
                                    </div>
                                </div>
                                <button type="submit" className="btn btn-primary" disabled={form.processing}>প্রদান করুন</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
