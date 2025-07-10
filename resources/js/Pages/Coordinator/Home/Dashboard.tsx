import React, { useState } from "react";
import { Head, usePage } from "@inertiajs/react";
import {
    Users,
    UserCheck,
    UserCog,
    ClipboardList,
    PercentCircle,
    CheckCircle,
    UserPlus,
} from "lucide-react";
import {
    BarChart,
    ResponsiveContainer,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Bar,
    LabelList,
} from "recharts";
import { CircularProgressbar, buildStyles } from "react-circular-progressbar";
import "react-circular-progressbar/dist/styles.css";

interface DashboardData {
    totalStudents: number;
    developmentStudents: number;
    researchStudents: number;
    activePanels: number;
    activeSupervisors: number;
    panelAssignedNumber: number;
    panelAssignedTotal: number;
    panelAssignedPercentage: number;
    supervisorAssignedNumber: number;
    supervisorAssignedTotal: number;
    supervisorAssignedPercentage: number;
    projectAreaAICounts: Record<string, number>;
}

interface DashboardProps {
    psm1: DashboardData;
    psm2: DashboardData;
}

const StatCard = ({
    icon: Icon,
    label,
    value,
    subValue,
}: {
    icon: any;
    label: string;
    value: string | number;
    subValue?: string;
}) => (
    <div className="flex items-center bg-white rounded-lg shadow p-5 gap-4">
        <div className="bg-blue-400 p-3 rounded-full">
            <Icon className="text-white w-7 h-7" />
        </div>
        <div>
            <div className="text-lg font-semibold">{value}</div>
            <div className="text-gray-500 text-sm">{label}</div>
            {subValue && (
                <div className="text-xs text-gray-400 mt-1">{subValue}</div>
            )}
        </div>
    </div>
);

const DashboardPSM1: React.FC = () => {
    const { psm1, psm2 } = usePage().props as unknown as DashboardProps;
    const [selectedPSM, setSelectedPSM] = useState<"psm1" | "psm2">("psm1");
    const data = selectedPSM === "psm1" ? psm1 : psm2;

    return (
        <div className="min-h-screen bg-gray-100 py-5 px-4 md:px-7">
            <Head title="PSM Dashboard" />
            <div className="flex pb-5">
                <div className="flex border border-blue-400 rounded overflow-hidden font-semibold">
                    <button
                        type="button"
                        className={`py-1 px-5 text-xl transition text-center ${
                            selectedPSM === "psm1"
                                ? "bg-blue-400 hover:bg-blue-500 text-white"
                                : "bg-white hover:bg-gray-100"
                        }`}
                        onClick={() => setSelectedPSM("psm1")}
                    >
                        PSM1
                    </button>
                    <button
                        type="button"
                        className={`py-1 px-5 text-xl transition text-center  ${
                            selectedPSM === "psm2"
                                ? "bg-blue-400 hover:bg-blue-500 text-white"
                                : "bg-white hover:bg-gray-100"
                        }`}
                        onClick={() => setSelectedPSM("psm2")}
                    >
                        PSM2
                    </button>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <StatCard
                    icon={Users}
                    label="Development Students"
                    value={data.developmentStudents}
                />
                <StatCard
                    icon={Users}
                    label="Research Students"
                    value={data.researchStudents}
                />
                <StatCard
                    icon={ClipboardList}
                    label={`Active ${selectedPSM.toUpperCase()} Panels`}
                    value={data.activePanels}
                />
                <StatCard
                    icon={ClipboardList}
                    label={`Active ${selectedPSM.toUpperCase()} Supervisors`}
                    value={data.activeSupervisors}
                />
                <StatCard
                    icon={() => (
                        <div className="w-10 h-10">
                            <CircularProgressbar
                                value={data.panelAssignedPercentage}
                                text={`${data.panelAssignedPercentage}%`}
                                strokeWidth={12}
                                styles={buildStyles({
                                    pathColor: "#3b82f6",
                                    trailColor: "#ffffff",
                                    textColor: "#ffffff",
                                    textSize: "24px",
                                })}
                            />
                        </div>
                    )}
                    label="Assigned to Panel"
                    value={`${data.panelAssignedPercentage}%`}
                    subValue={`${data.panelAssignedNumber} / ${data.panelAssignedTotal}`}
                />
                <StatCard
                    icon={() => (
                        <div className="w-10 h-10">
                            <CircularProgressbar
                                value={data.supervisorAssignedPercentage}
                                text={`${data.supervisorAssignedPercentage}%`}
                                strokeWidth={12}
                                styles={buildStyles({
                                    pathColor: "#3b82f6",
                                    trailColor: "#ffffff",
                                    textColor: "#ffffff",
                                    textSize: "24px",
                                })}
                            />
                        </div>
                    )}
                    label="Assigned to Supervisor"
                    value={`${data.supervisorAssignedPercentage}%`}
                    subValue={`${data.supervisorAssignedNumber} / ${data.supervisorAssignedTotal}`}
                />
            </div>
            {/* Project Area AI Visualization */}
            <div className="bg-white rounded-lg shadow p-6 mb-3">
                <h2 className="text-xl font-semibold mb-4 text-black">
                    Student Project Area Distribution
                </h2>
                {Object.entries(data.projectAreaAICounts).length === 0 ? (
                    <div className="text-gray-500">No data available.</div>
                ) : (
                    <div className="w-full h-96">
                        <ResponsiveContainer width="100%" height="100%">
                            <BarChart
                                data={Object.entries(
                                    data.projectAreaAICounts
                                ).map(([area, count]) => ({
                                    area: area || "Unspecified",
                                    count,
                                }))}
                                layout="vertical"
                                margin={{
                                    top: 10,
                                    right: 30,
                                    left: 10,
                                    bottom: 10,
                                }}
                            >
                                <CartesianGrid strokeDasharray="3 3" />
                                <XAxis type="number" allowDecimals={false} />
                                <YAxis
                                    dataKey="area"
                                    type="category"
                                    width={150}
                                />
                                <Tooltip />
                                <Bar dataKey="count" fill="#60a5fa">
                                    <LabelList
                                        dataKey="count"
                                        position="right"
                                    />
                                </Bar>
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                )}
            </div>
        </div>
    );
};

export default DashboardPSM1;
