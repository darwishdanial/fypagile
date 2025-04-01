import React, { useState, useEffect } from "react";
import { usePage } from "@inertiajs/react";

interface Flash {
    error?: string;
    success?: string;
}

export default function PanelHistory() {
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
    }, [props.flash]); // Run effect when flash message changes

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

            <h1 className="text-2xl font-semibold text-gray-900 m-5">
                Panel History
            </h1>
        </div>
    );
}
