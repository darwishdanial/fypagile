import React, { useState, useEffect } from "react";
import { router, useForm, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
import { X } from "lucide-react";

interface Criteria {
    id: number;
    rubric_id: number;
    name: string;
    weight: number;
}

interface EditCriteriaModalProps {
    isOpen: boolean;
    onClose: () => void;
    criteria: Criteria | null;
    psmType: string;
}

const EditCriteriaModal: React.FC<EditCriteriaModalProps> = ({
    isOpen,
    onClose,
    criteria,
    psmType,
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
        rubric_id: criteria?.rubric_id || "",
        name: criteria?.name || "",
        weight: criteria?.weight || "",
    });

    useEffect(() => {
        if (criteria) {
            setData({
                rubric_id: criteria.rubric_id || "",
                name: criteria.name || "",
                weight: criteria.weight || "",
            });
        }
    }, [criteria, setData]);

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

        if (!criteria) return;

        const route_path =
            psmType === "PSM1"
                ? "coordinator.PSM1.evaluationCriteria.update"
                : "coordinator.PSM2.evaluationCriteria.update";

        router.put(route(route_path, criteria.id), data, {
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
                    rubric_id: criteria?.rubric_id || "",
                    name: criteria?.name || "",
                    weight: criteria?.weight || "",
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
                    <h2 className="text-lg font-semibold">Edit Criteria</h2>
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
                    <form id="PSM1EditCriteriaForm" onSubmit={handleSubmit}>
                        <div className="p-4 border rounded border-gray-300 my-3 mx-2">
                            <h3 className="font-medium text-[#808080] mb-3">
                                Criteria Information
                            </h3>

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Name:
                                </label>
                                <input
                                    title="Name"
                                    type="text"
                                    name="name"
                                    value={data.name}
                                    onChange={handleChange}
                                    className="col-span-4 border border-gray-300 rounded p-2 w-full"
                                    required
                                />
                                {errors.name && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.name}
                                    </p>
                                )}
                            </div>
                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Weight:
                                </label>
                                <input
                                    title="Weight"
                                    type="decimal"
                                    name="weight"
                                    value={data.weight}
                                    onChange={handleChange}
                                    className="col-span-4 border border-gray-300 rounded p-2 w-full"
                                    required
                                />
                                {errors.weight && (
                                    <p className="text-red-500 col-start-2 col-span-4">
                                        {errors.weight}
                                    </p>
                                )}
                            </div>
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
                    <button
                        type="submit"
                        form="PSM1EditCriteriaForm"
                        disabled={processing}
                        className={`px-4 py-2 bg-blue-400 text-white rounded hover:bg-blue-500 transition ${
                            processing ? "opacity-50 cursor-not-allowed" : ""
                        }`}
                    >
                        {processing ? "Saving..." : "Save"}
                    </button>
                </div>
            </div>
        </div>
    );
};

export default EditCriteriaModal;
