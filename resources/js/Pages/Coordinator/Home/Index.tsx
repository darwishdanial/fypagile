import React from "react";
import StatsCard from "../../../Components/StatsCard";

export default function Index() {
    return (
        <div className="min-h-screen bg-gray-100 ">
            <div className="w-full flex flex-col items-center justify-center space-y-6 pt-15">
                <StatsCard studentCount={220} panelCount={110} />
                <StatsCard studentCount={220} panelCount={110} />
            </div>
        </div>
    );
}
