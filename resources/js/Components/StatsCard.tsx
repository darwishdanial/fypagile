import React from "react";
import { Users, UserCheck } from "lucide-react";

interface StatsCardProps {
    studentCount: number;
    panelCount: number;
    studentText: string;
    panelText: string;
}

const StatsCard: React.FC<StatsCardProps> = ({ studentCount, panelCount, studentText, panelText }) => {
    return (
        <div className="flex justify-center items-center bg-white py-10 px-15 rounded-lg shadow-lg">
            <div className="flex text-center items-center">
                <Users size={64} />
                <div className="pl-4">
                    <div className="text-[64px] font-bold text-black items-center">
                        {studentCount}
                    </div>
                    <div className="text-sm text-gray-600">{studentText}</div>
                </div>
            </div>
            <div className="border-l border-gray-300 h-40 mx-8"></div>
            <div className="flex text-center items-center">
                <UserCheck size={64} className="text-[#6D2323]" />
                <div className="pl-4">
                    <div className="text-[64px] font-bold text-[#6D2323]">
                        {panelCount}
                    </div>
                    <div className="text-sm text-gray-600">{panelText}</div>
                </div>
            </div>
        </div>
    );
};

export default StatsCard;
