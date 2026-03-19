import React, { useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const form = useForm({ username: '', password: '', remember: false });

    const handleSubmit = (e) => {
        e.preventDefault();
        form.post('/login');
    };

    useEffect(() => {
        if (typeof feather !== 'undefined') feather.replace();
    }, []);

    return (
        <>
            <Head title="Login" />
            <div className="codex-signin" style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}>
                <div className="card" style={{ width: 'min(400px, 92vw)', borderRadius: '15px' }}>
                    <div className="card-body p-4">
                        <div className="text-center mb-4">
                            <h3>CPDS-DK-BS</h3>
                            <p className="text-muted">লগইন করুন</p>
                        </div>

                        {status && <div className="alert alert-success">{status}</div>}

                        <form onSubmit={handleSubmit}>
                            <div className="mb-3">
                                <label className="form-label">ইউজারনেম</label>
                                <input type="text" className="form-control" value={form.data.username}
                                    onChange={e => form.setData('username', e.target.value)} required autoFocus />
                                {form.errors.username && <small className="text-danger">{form.errors.username}</small>}
                            </div>
                            <div className="mb-3">
                                <label className="form-label">পাসওয়ার্ড</label>
                                <input type="password" className="form-control" value={form.data.password}
                                    onChange={e => form.setData('password', e.target.value)} required />
                                {form.errors.password && <small className="text-danger">{form.errors.password}</small>}
                            </div>
                            <div className="mb-3 form-check">
                                <input type="checkbox" className="form-check-input" checked={form.data.remember}
                                    onChange={e => form.setData('remember', e.target.checked)} />
                                <label className="form-check-label">মনে রাখুন</label>
                            </div>
                            <button type="submit" className="btn btn-primary w-100" disabled={form.processing}>
                                {form.processing ? 'Loading...' : 'লগইন'}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </>
    );
}
