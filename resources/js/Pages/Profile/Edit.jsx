import React, { useEffect } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';

export default function Edit() {
    const { auth } = usePage().props;
    const user = auth?.user;

    const profileForm = useForm({ name: user?.name || '', email: user?.email || '' });
    const passwordForm = useForm({ current_password: '', password: '', password_confirmation: '' });

    const handleProfileUpdate = (e) => {
        e.preventDefault();
        profileForm.patch('/profile');
    };

    const handlePasswordUpdate = (e) => {
        e.preventDefault();
        passwordForm.put('/password', { onSuccess: () => passwordForm.reset() });
    };

    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    return (
        <AdminLayout>
            <Head title="প্রোফাইল" />
            <Breadcrumb title="প্রোফাইল" items={[{ label: 'সম্পাদনা', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="row">
                        <div className="col-md-6">
                            <div className="card">
                                <div className="card-header"><h4>প্রোফাইল তথ্য</h4></div>
                                <div className="card-body">
                                    <form onSubmit={handleProfileUpdate}>
                                        <div className="mb-3">
                                            <label className="form-label">নাম</label>
                                            <input type="text" className="form-control" value={profileForm.data.name} onChange={e => profileForm.setData('name', e.target.value)} />
                                        </div>
                                        <div className="mb-3">
                                            <label className="form-label">ইমেইল</label>
                                            <input type="email" className="form-control" value={profileForm.data.email} onChange={e => profileForm.setData('email', e.target.value)} />
                                        </div>
                                        <button type="submit" className="btn btn-primary" disabled={profileForm.processing}>আপডেট</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-6">
                            <div className="card">
                                <div className="card-header"><h4>পাসওয়ার্ড পরিবর্তন</h4></div>
                                <div className="card-body">
                                    <form onSubmit={handlePasswordUpdate}>
                                        <div className="mb-3">
                                            <label className="form-label">বর্তমান পাসওয়ার্ড</label>
                                            <input type="password" className="form-control" value={passwordForm.data.current_password} onChange={e => passwordForm.setData('current_password', e.target.value)} />
                                        </div>
                                        <div className="mb-3">
                                            <label className="form-label">নতুন পাসওয়ার্ড</label>
                                            <input type="password" className="form-control" value={passwordForm.data.password} onChange={e => passwordForm.setData('password', e.target.value)} />
                                        </div>
                                        <div className="mb-3">
                                            <label className="form-label">পাসওয়ার্ড নিশ্চিত</label>
                                            <input type="password" className="form-control" value={passwordForm.data.password_confirmation} onChange={e => passwordForm.setData('password_confirmation', e.target.value)} />
                                        </div>
                                        <button type="submit" className="btn btn-primary" disabled={passwordForm.processing}>পরিবর্তন</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
