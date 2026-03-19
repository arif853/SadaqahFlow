import React from 'react';
import { Head, useForm } from '@inertiajs/react';

export default function ResetPassword({ token, email }) {
    const form = useForm({ token, email, password: '', password_confirmation: '' });
    const handleSubmit = (e) => { e.preventDefault(); form.post('/reset-password'); };

    return (
        <>
            <Head title="Reset Password" />
            <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}>
                <div className="card" style={{ width: '400px', borderRadius: '15px' }}>
                    <div className="card-body p-4">
                        <h3 className="text-center mb-4">পাসওয়ার্ড রিসেট</h3>
                        <form onSubmit={handleSubmit}>
                            <div className="mb-3"><label className="form-label">ইমেইল</label><input type="email" className="form-control" value={form.data.email} onChange={e => form.setData('email', e.target.value)} required /></div>
                            <div className="mb-3"><label className="form-label">নতুন পাসওয়ার্ড</label><input type="password" className="form-control" value={form.data.password} onChange={e => form.setData('password', e.target.value)} required /></div>
                            <div className="mb-3"><label className="form-label">পাসওয়ার্ড নিশ্চিত</label><input type="password" className="form-control" value={form.data.password_confirmation} onChange={e => form.setData('password_confirmation', e.target.value)} required /></div>
                            <button type="submit" className="btn btn-primary w-100" disabled={form.processing}>রিসেট</button>
                        </form>
                    </div>
                </div>
            </div>
        </>
    );
}
