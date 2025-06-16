import React, { useState, useEffect } from "react";
import StatsCard from "../../../Components/StatsCard";
import { usePage } from "@inertiajs/react";

interface Props  {
    userName: string;
    studentsPSM1: number;
    studentsPSM2: number
    panelsPSM1: number
    panelsPSM2: number
}
interface Flash {
    error?: string;
    success?: string;
}

export default function Index({ userName, studentsPSM1, studentsPSM2, panelsPSM1, panelsPSM2 }: Props) {
    const { props } = usePage<{
        flash: Flash;
    }>();

    const [flashMessage, setFlashMessage] = useState<{
        type: "success" | "error";
        message: string;
    } | null>(null);

    useEffect(() => {
        if (props.flash?.success) {
            setFlashMessage({ type: "success", message: props.flash.success });
        }
        if (props.flash?.error) {
            setFlashMessage({ type: "error", message: props.flash.error });
        }

        if (props.flash?.success || props.flash?.error) {
            const timer = setTimeout(() => setFlashMessage(null), 3000); // Hide after 3s
            return () => clearTimeout(timer);
        }
    }, [props.flash]); 

    return (
        <div className="min-h-screen bg-gray-100 flex flex-col space-y-3">
            {flashMessage && (
                <div
                    className={`fixed bottom-5 right-5 px-4 py-3 rounded shadow-lg text-white ${
                        flashMessage.type === "success"
                            ? "bg-green-600"
                            : "bg-red-600"
                    }`}
                >
                    {flashMessage.message}
                </div>
            )}
            <p className="pl-5 pt-5">Welcome, {userName}</p>
            <div className="w-full flex flex-col items-center justify-center space-y-6 px-15">
                <StatsCard
                    studentCount={studentsPSM1}
                    panelCount={panelsPSM1}
                    studentText="PSM1 Students under supervision"
                    panelText="PSM1 Students under your review"
                />
                <StatsCard
                    studentCount={studentsPSM2}
                    panelCount={panelsPSM2}
                    studentText="PSM2 Students under supervision"
                    panelText="PSM2 Students under your review"
                />
            </div>
        </div>
    );
}
