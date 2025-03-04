import React from "react";
import StatsCard from "../../../Components/StatsCard";

interface Props  {
    userName: string;
}

export default function Index( { userName }: Props) {
    return (
        <div className="min-h-screen bg-gray-100 flex flex-col space-y-3">
            <p className="pl-5 pt-5">Welcome, {userName}</p>
            <div className="w-full flex flex-col items-center justify-center space-y-6 px-15">
                <StatsCard studentCount={220} panelCount={110} studentText="PSM1 Students under supervision" panelText="PSM1 Students under your review"/>
                <StatsCard studentCount={220} panelCount={110} studentText="PSM2 Students under supervision" panelText="PSM2 Students under your review"/>
            </div>
        </div>
    );
}
