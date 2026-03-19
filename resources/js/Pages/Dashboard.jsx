import React, { useEffect, useRef } from 'react';
import { Head, usePage } from '@inertiajs/react';
import AdminLayout from '../Layouts/AdminLayout';
import Breadcrumb from '../Components/Breadcrumb';

export default function Dashboard({
    users, khedmots, chartUsers, totalMembers,
    totalKhedmotAmount, totalRentAmount, totalKalyanAmount, totalManatAmount,
    KhedmotAmount, RentAmount, KalyanAmount, ManatAmount,
    totalCentralFunds
}) {
    const { auth } = usePage().props;
    const roles = auth?.user?.roles || [];
    const isAdmin = roles.includes('Super Admin') || roles.includes('Admin');
    const chartRef = useRef(null);

    const programName = (val, other) => {
        if (val == 1) return 'ওরশ পাক';
        if (val == 2) return 'বেসালত দিবস';
        if (val == 3) return 'জলসায়ে ওরশ পাক';
        if (val == 4) return `অনন্যা - ${other || ''}`;
        return '';
    };

    useEffect(() => {
        if (isAdmin && chartRef.current && typeof ApexCharts !== 'undefined' && chartUsers) {
            const options = {
                series: [{
                    data: chartUsers.map(u => {
                        const sum = (u.khedmots || []).reduce((acc, k) => acc + (parseFloat(k.khedmot_amount) || 0), 0);
                        return sum;
                    })
                }],
                chart: { height: 400, type: 'bar' },
                plotOptions: { bar: { columnWidth: '40%', distributed: true } },
                dataLabels: { enabled: true },
                legend: { show: true },
                xaxis: {
                    categories: chartUsers.map(u => [u.name]),
                    labels: { style: { fontSize: '12px' } }
                }
            };
            const chart = new ApexCharts(chartRef.current, options);
            chart.render();
            return () => chart.destroy();
        }
    }, [chartUsers, isAdmin]);

    // Re-init feather icons after render
    useEffect(() => {
        if (typeof feather !== 'undefined') feather.replace();
    }, []);

    return (
        <AdminLayout>
            <Head title="Dashboard" />
            <Breadcrumb title="Dashboard" items={[{ label: 'Default', active: true }]} />

            <style>{`
                .custome-card-bg { background: #fbcccc !important; }
                .custome-card-bg-2 { background: #c0f08f !important; }
            `}</style>

            <div className="theme-body common-dash" data-simplebar>
                <div className="custom-container">
                    <div className="row">
                        <div className="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 cdx-xl-100">
                            <div className="row">
                                {isAdmin && (
                                    <div className="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                        <div className="card project-status custome-card-bg-2">
                                            <div className="card-header"><h6>সর্বমোট(Cash Box)</h6></div>
                                            <div className="card-body progressCounter">
                                                <div className="media"><div><h5>&#2547 <span>{totalCentralFunds}</span></h5></div></div>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {[
                                    { label: 'খেদমত(সংগ্রহ)', value: KhedmotAmount, bg: 'custome-card-bg' },
                                    { label: 'মানত(সংগ্রহ)', value: ManatAmount, bg: 'custome-card-bg' },
                                    { label: 'কল্যাণ(সংগ্রহ)', value: KalyanAmount, bg: 'custome-card-bg' },
                                    { label: 'ভাড়া(সংগ্রহ)', value: RentAmount, bg: 'custome-card-bg' },
                                    { label: 'খেদমত(জমা)', value: totalKhedmotAmount, bg: '' },
                                    { label: 'মানত(জমা)', value: totalManatAmount, bg: '' },
                                    { label: 'কল্যাণ (জমা)', value: totalKalyanAmount, bg: '' },
                                    { label: 'ভাড়া (জমা)', value: totalRentAmount, bg: '' },
                                ].map((card, idx) => (
                                    <div key={idx} className="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                        <div className={`card project-status ${card.bg}`}>
                                            <div className="card-header"><h6>{card.label}</h6></div>
                                            <div className="card-body progressCounter">
                                                <div className="media"><div><h5>&#2547 <span>{card.value}</span></h5></div></div>
                                            </div>
                                        </div>
                                    </div>
                                ))}

                                <div className="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div className="card project-status">
                                        <div className="card-header"><h6>জাকেরদের সংখ্যা</h6></div>
                                        <div className="card-body progressCounter">
                                            <div className="media"><div><h5><span>{totalMembers}</span> জন</h5></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {isAdmin && (
                            <div className="col-xl-6 cdx-xl-50">
                                <div className="card project-summarytbl">
                                    <div className="card-header"><h4>খেদমত গ্রহনকারি করমি তালিকা</h4></div>
                                    <div className="card-body">
                                        <div className="table-responsive">
                                            <table className="table">
                                                <thead>
                                                    <tr>
                                                        <th>নং</th><th>করমির নাম</th><th>ফোন</th><th>ঠিকানা</th><th>প্রক্রিয়া</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {(users || []).map((user, idx) => (
                                                        <tr key={user.id}>
                                                            <td>{idx + 1}</td>
                                                            <td>{user.name}</td>
                                                            <td>{user.phone}</td>
                                                            <td>{user.address}</td>
                                                            <td></td>
                                                        </tr>
                                                    ))}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )}

                        <div className="col-xl-6 cdx-xl-50">
                            <div className="card recent-ordertbl">
                                <div className="card-header"><h4>শেষ ১০টি সংগৃহীত খেদমত</h4></div>
                                <div className="card-body">
                                    <div className="table-responsive">
                                        <table className="table">
                                            <thead>
                                                <tr>
                                                    <th>নং</th><th>জাকের নাম</th><th>তারিখ</th><th>অনুষ্ঠান</th><th>খেদমত পরিমাণ</th><th>কর্মী নাম</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {(khedmots || []).map((k, idx) => (
                                                    <tr key={k.id}>
                                                        <td>{idx + 1}</td>
                                                        <td>{k.member?.name}</td>
                                                        <td>{k.date}</td>
                                                        <td>{programName(k.program_name, k.other_program_name)}</td>
                                                        <td>{k.khedmot_amount}</td>
                                                        <td>{k.user?.name}</td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {isAdmin && (
                            <div className="col-xl-12 cdx-xl-50">
                                <div className="card visitor-ratetbl">
                                    <div className="card-header"><h4>করমিদের খেদমত সংগ্রহ</h4></div>
                                    <div className="card-body p-0">
                                        <div ref={chartRef} id="apex-columnchart"></div>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
