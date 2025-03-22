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
    studentId: number | null;
}

const GradeRubricModal: React.FC<GradeRubricModalProps> = ({
    isOpen,
    onClose,
    psmType,
    rubric,
    studentId,
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
        id: rubric?.id || "",
        name: rubric?.name || "",
        total_weight: rubric?.total_weight || "",
        PSMType: psmType || "",
        isEnable: rubric?.isEnable || false,
        isSupervisorPSM1: rubric?.isSupervisorPSM1 || false,
        isPanelPSM1: rubric?.isPanelPSM1 || false,
        isSupervisorPSM2: rubric?.isSupervisorPSM2 || false,
        isPanelPSM2: rubric?.isPanelPSM2 || false,
        comments: "",
    });

    useEffect(() => {
        if (rubric) {
            setData({
                id: rubric?.id || "",
                name: rubric?.name || "",
                total_weight: rubric?.total_weight || "",
                PSMType: psmType || "",
                isEnable: rubric?.isEnable || false,
                isSupervisorPSM1: rubric?.isSupervisorPSM1 || false,
                isPanelPSM1: rubric?.isPanelPSM1 || false,
                isSupervisorPSM2: rubric?.isSupervisorPSM2 || false,
                isPanelPSM2: rubric?.isPanelPSM2 || false,
                comments: "",
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

        if (!rubric || !studentId) return;

        // Extract only criteria scores
        const criteriaScores = {};
        rubric.criteria?.forEach((criterion) => {
            if (data[`criteria_${criterion.id}`] !== undefined) {
                criteriaScores[criterion.id] = data[`criteria_${criterion.id}`];
            }
        });

        // Send only the criteria scores
        router.post(
            route("coordinator.PSM1.score.store", rubric.id),
            { criteria: criteriaScores, student_id: studentId, comments: data.comments, total_weight: data.total_weight, rubric_id:data.id },
            {
                onStart: () => setIsProcessing(true),
                onFinish: () => setIsProcessing(false),
                onError: (errors) => console.log(errors),
                onSuccess: () => {
                    onClose();
                },
            }
        );
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
                        Grade {rubric?.name || "N/A"} {""}
                        {rubric?.total_weight || "N/A"}%
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

                <div className="overflow-y-auto max-h-[80vh]">
                    <form id="PSM1GradeRubricForm" onSubmit={handleSubmit}>
                        <div className="p-4 border rounded border-gray-300 my-3 mx-2">
                            <h3 className="font-medium text-[#808080] mb-3">
                                Criteria
                            </h3>

                            {rubric?.isEnable ? (
                                <>
                                    {rubric?.criteria?.map((criterion) => (
                                        <div
                                            key={criterion.id}
                                            className="mb-4 flex"
                                        >
                                            <label className="font-medium text-gray-700 mr-4 mt-1">
                                                {criterion.name}
                                            </label>

                                            <div className="flex gap-4 mt-2">
                                                {[1, 2, 3, 4].map((score) => (
                                                    <label
                                                        key={score}
                                                        className="flex items-center gap-2"
                                                    >
                                                        <input
                                                            type="radio"
                                                            name={`criteria_${criterion.id}`}
                                                            value={score/4 * criterion.weight}
                                                            checked={
                                                                data[
                                                                    `criteria_${criterion.id}`
                                                                ] === score/4 * criterion.weight
                                                            }
                                                            onChange={(e) =>
                                                                setData(
                                                                    (prev) => ({
                                                                        ...prev,
                                                                        [`criteria_${criterion.id}`]:
                                                                            Number(
                                                                                e
                                                                                    .target
                                                                                    .value
                                                                            ),
                                                                    })
                                                                )
                                                            }
                                                            className="cursor-pointer"
                                                        />
                                                        {score}
                                                    </label>
                                                ))}
                                            </div>
                                        </div>
                                    ))}

                                    {/* Comment Section (Only visible if rubric is enabled) */}
                                    <div className="mt-4">
                                        <label className="font-medium text-gray-700">
                                            Comments (Optional)
                                        </label>
                                        <textarea
                                            name="comments"
                                            value={data.comments}
                                            onChange={(e) =>
                                                setData(
                                                    "comments",
                                                    e.target.value
                                                )
                                            }
                                            rows={4}
                                            className="w-full mt-2 p-2 border rounded border-gray-300"
                                            placeholder="Enter your comments here..."
                                        ></textarea>
                                    </div>
                                </>
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
