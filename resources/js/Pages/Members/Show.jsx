import React, { useEffect } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';

export default function Show({ member, khedmots }) {
    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    return (
        <AdminLayout>
            <Head title={`জাকের - ${member.name}`} />
            <Breadcrumb title="জাকের বিস্তারিত" items={[{ label: member.name, active: true }]} />

            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="row">
                        <div className="col-md-4">
                            <div className="card">
                                <div className="card-body text-center">
                                    {member.image ? (
                                        <img src={`/storage/${member.image}`} className="rounded-circle mb-3" width="120" height="120" alt="" />
                                    ) : (
                                        <div className="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3" style={{ width: 120, height: 120 }}>
                                            <i className="fa fa-user fa-3x text-white"></i>
                                        </div>
                                    )}
                                    <h4>{member.name}</h4>
                                    <p>{member.nickName && `(${member.nickName})`}</p>
                                    <span className={`badge bg-${member.status == 1 ? 'success' : 'danger'}`}>
                                        {member.status == 1 ? 'Active' : 'Inactive'}
                                    </span>
                                </div>
                            </div>
                            <div className="card">
                                <div className="card-header"><h6>তথ্য</h6></div>
                                <div className="card-body">
                                    <table className="table table-borderless">
                                        <tbody>
                                            <tr><td><strong>কল্যাণ নং:</strong></td><td>{member.kollan_id}</td></tr>
                                            <tr><td><strong>পিতার নাম:</strong></td><td>{member.father_name}</td></tr>
                                            <tr><td><strong>স্ত্রীর নাম:</strong></td><td>{member.spouse_name}</td></tr>
                                            <tr><td><strong>ফোন:</strong></td><td>{member.phone}</td></tr>
                                            <tr><td><strong>রক্তের গ্রুপ:</strong></td><td>{member.bloodType}</td></tr>
                                            <tr><td><strong>কল্যাণ খেদমত:</strong></td><td>{member.kollan_khedmot}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-8">
                            <div className="card">
                                <div className="card-header"><h4>খেদমত তালিকা</h4></div>
                                <div className="card-body">
                                    <div className="table-responsive">
                                        <table className="table">
                                            <thead>
                                                <tr>
                                                    <th>নং</th><th>তারিখ</th><th>খেদমত</th><th>মানত</th><th>কল্যাণ</th><th>ভাড়া</th><th>স্টেটাস</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {(khedmots || []).map((k, idx) => (
                                                    <tr key={k.id}>
                                                        <td>{idx + 1}</td>
                                                        <td>{k.date}</td>
                                                        <td>{k.khedmot_amount || 0}</td>
                                                        <td>{k.manat_amount || 0}</td>
                                                        <td>{k.kalyan_amount || 0}</td>
                                                        <td>{k.rent_amount || 0}</td>
                                                        <td>
                                                            <span className={`badge bg-${k.is_collected ? 'success' : 'warning'}`}>
                                                                {k.is_collected ? 'জমা' : 'অজমা'}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
