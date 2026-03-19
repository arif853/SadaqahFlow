import React, { useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';

export default function GivePermission({ role, permissions, groupedPermissions, rolePermission }) {
    const rolePermNames = (rolePermission || []).map(p => p.name);
    const [selected, setSelected] = useState(rolePermNames);

    const toggle = (name) => {
        setSelected(prev => prev.includes(name) ? prev.filter(n => n !== name) : [...prev, name]);
    };

    const form = useForm({ permission: selected });

    const handleSubmit = (e) => {
        e.preventDefault();
        form.setData('permission', selected);
        form.put(`/dashboard/users/roles/${role.id}/give-permissions`);
    };

    useEffect(() => { form.setData('permission', selected); }, [selected]);
    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    return (
        <AdminLayout>
            <Head title={`পারমিশন - ${role.name}`} />
            <Breadcrumb title={`রোল: ${role.name}`} items={[{ label: 'পারমিশন অ্যাসাইন', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header"><h4>পারমিশন অ্যাসাইন করুন - {role.name}</h4></div>
                        <div className="card-body">
                            <form onSubmit={handleSubmit}>
                                {Object.entries(groupedPermissions || {}).map(([category, actions]) => (
                                    <div key={category} className="mb-4">
                                        <h6 className="text-primary text-capitalize">{category}</h6>
                                        <div className="row">
                                            {Object.entries(actions || {}).map(([action, perms]) => (
                                                perms.map(p => (
                                                    <div key={p.id} className="col-md-3 mb-2">
                                                        <label className="form-check-label">
                                                            <input type="checkbox" className="form-check-input me-2"
                                                                checked={selected.includes(p.name)}
                                                                onChange={() => toggle(p.name)} />
                                                            {p.name}
                                                        </label>
                                                    </div>
                                                ))
                                            ))}
                                        </div>
                                    </div>
                                ))}
                                <button type="submit" className="btn btn-primary" disabled={form.processing}>সংরক্ষণ করুন</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
