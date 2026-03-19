import React from 'react';
import { Head, useForm } from '@inertiajs/react';

export default function ForgotPassword({ status }) {
    const form = useForm({ email: '' });
    const handleSubmit = (e) => { e.preventDefault(); form.post('/forgot-password'); };

    return (
        <>
            <Head title="Forgot Password" />
            <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}>
                <div className="card" style={{ width: '400px', borderRadius: '15px' }}>
                    <div className="card-body p-4">
                        <h3 className="text-center mb-4">পাসওয়ার্ড ভুলে গেছেন?</h3>
                        {status && <div className="alert alert-success">{status}</div>}
                        <form onSubmit={handleSubmit}>
                            <div className="mb-3"><label className="form-label">ইমেইল</label><input type="email" className="form-control" value={form.data.email} onChange={e => form.setData('email', e.target.value)} required /></div>
                            <button type="submit" className="btn btn-primary w-100" disabled={form.processing}>রিসেট লিংক পাঠান</button>
                        </form>
                    </div>
                </div>
            </div>
        </>
    );
}
