import React from "react";
import StatsCard from "../../../Components/StatsCard";

export default function Index() {
    return (
        <div className="min-h-screen bg-gray-100 ">
            <p className="pl-5 pt-5">Welcome, user</p>
            <div className="w-full flex flex-col items-center justify-center space-y-6 pt-10">
                <StatsCard studentCount={220} panelCount={110} studentText="PSM1 Students" panelText="PSM1 Panels"/>
                <StatsCard studentCount={220} panelCount={110} studentText="PSM2 Students" panelText="PSM2 Panels"/>
            </div>
        </div>
    );
}
