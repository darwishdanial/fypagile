import React, { useState, useEffect } from "react";
import { router, useForm, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
import { X } from "lucide-react";

interface Rubric {
    id: number;
    name: string;
    total_weight: number;
    psmType: string;
    isEnable: boolean;
    isSupervisorPSM1: boolean;
    isPanelPSM1: boolean;
    isArchivePSM1: boolean;
    isSupervisorPSM2: boolean;
    isPanelPSM2: boolean;
    criteria: Criteria[] | null;
}

interface Criteria {
    id: number;
    rubric_id: number;
    name: string;
    weight: number;
}

interface GradeRubricModalProps {
    isOpen: boolean;
    onClose: () => void;
    psmType: string;
    rubric: Rubric | null;
}

const GradeRubricModal: React.FC<GradeRubricModalProps> = ({
    isOpen,
    onClose,
    psmType,
    rubric,
}) => {
    useEffect(() => {
        if (isOpen) {
            // Disable scrolling on body when modal is open
            document.body.style.overflow = "hidden";
        }

        return () => {
            // Re-enable scrolling when component unmounts or modal closes
            document.body.style.overflow = "unset";
        };
    }, [isOpen]);

    const { data, setData } = useForm({
        name: rubric?.name || "",
        total_weight: rubric?.total_weight || "",
        PSMType: psmType || "",
        isEnable: rubric?.isEnable || false,
        isSupervisorPSM1: rubric?.isSupervisorPSM1 || false,
        isPanelPSM1: rubric?.isPanelPSM1 || false,
        isSupervisorPSM2: rubric?.isSupervisorPSM2 || false,
        isPanelPSM2: rubric?.isPanelPSM2 || false,
    });

    useEffect(() => {
        if (rubric) {
            setData({
                name: rubric?.name || "",
                total_weight: rubric?.total_weight || "",
                PSMType: psmType || "",
                isEnable: rubric?.isEnable || false,
                isSupervisorPSM1: rubric?.isSupervisorPSM1 || false,
                isPanelPSM1: rubric?.isPanelPSM1 || false,
                isSupervisorPSM2: rubric?.isSupervisorPSM2 || false,
                isPanelPSM2: rubric?.isPanelPSM2 || false,
            });
        }
    }, [rubric, setData]);

    const [processing, setIsProcessing] = useState(false);

    useEffect(() => {
        console.log("process:", processing);
    }, [processing]);

    const { errors } = usePage().props;

    const handleChange = (
        e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
    ) => {
        const { name, value, type } = e.target as HTMLInputElement;

        if (type === "checkbox") {
            const isChecked = (e.target as HTMLInputElement).checked;
            setData((prev) => ({
                ...prev,
                [name]: isChecked,
            }));
        } else {
            setData((prev) => ({
                ...prev,
                [name]: value,
            }));
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        if (!rubric) return;

        const route_path =
            psmType === "PSM1"
                ? "coordinator.PSM1.evaluationRubric.grade"
                : "coordinator.PSM2.evaluationRubric.grade";

        router.put(route(route_path, rubric.id), data, {
            onStart: () => {
                setIsProcessing(true);
            },
            onFinish: () => {
                setIsProcessing(false);
            },
            onError: (errors) => {
                console.log(errors);
            },
            onSuccess: () => {
                onClose();
                // Reset form
                setData({
                    name: rubric?.name || "",
                    total_weight: rubric?.total_weight || "",
                    PSMType: psmType || "",
                    isEnable: rubric?.isEnable || false,
                    isSupervisorPSM1: rubric?.isSupervisorPSM1 || false,
                    isPanelPSM1: rubric?.isPanelPSM1 || false,
                    isSupervisorPSM2: rubric?.isSupervisorPSM2 || false,
                    isPanelPSM2: rubric?.isPanelPSM2 || false,
                });
            },
        });
    };

    if (!isOpen) return null;

    return (
        <div
            className="fixed inset-0 bg-black/20 flex items-center justify-center z-50"
            onClick={onClose}
        >
            <div
                className="bg-white rounded-lg w-full max-w-lg xl:max-w-2xl shadow-xl"
                onClick={(e) => e.stopPropagation()}
            >
                <div className="flex justify-between items-center py-2 px-4">
                    <h2 className="text-lg font-semibold">
                        Grade {rubric?.name || "N/A"}{" "}
                    </h2>
                    <button
                        title="close"
                        type="button"
                        onClick={onClose}
                        className="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded p-1"
                    >
                        <X size={20} />
                    </button>
                </div>

                <hr className="border-t-1 border-gray-300"></hr>

                <div className="overflow-y-auto">
                    <form id="PSM1GradeRubricForm" onSubmit={handleSubmit}>
                        <div className="p-4 border rounded border-gray-300 my-3 mx-2">
                            <h3 className="font-medium text-[#808080] mb-3">
                                Rubric Information
                            </h3>

                            {rubric?.isEnable ? (
                                <p className="text-green-500">Rubric enabled</p>
                            ) : (
                                <p className="text-red-500">Rubric disabled</p>
                            )}
                        </div>
                    </form>
                </div>

                <hr className="border-t-1 border-gray-300"></hr>

                <div className="flex justify-end mx-2 my-3">
                    <button
                        type="button"
                        onClick={onClose}
                        className="mr-2 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition"
                    >
                        Cancel
                    </button>

                    {Boolean(rubric?.isEnable) && (
                        <button
                            type="submit"
                            form="PSM1GradeRubricForm"
                            disabled={processing}
                            className={`px-4 py-2 bg-blue-400 text-white rounded hover:bg-blue-500 transition ${
                                processing
                                    ? "opacity-50 cursor-not-allowed"
                                    : ""
                            }`}
                        >
                            {processing ? "Saving..." : "Save"}
                        </button>
                    )}
                </div>
            </div>
        </div>
    );
};

export default GradeRubricModal;
