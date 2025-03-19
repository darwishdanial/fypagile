import React, { useState, useEffect } from "react";
import { router, useForm, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
import { X } from "lucide-react";

interface AddRubricModalProps {
    isOpen: boolean;
    onClose: () => void;
    psmType: string;
}

const AddRubricModal: React.FC<AddRubricModalProps> = ({
    isOpen,
    onClose,
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
        name: "",
        total_weight: "",
        psmTypee: psmType,
        isSupervisorPSM1: false,
        isPanelPSM1: false,
        isArchivePSM1: false,
        isSupervisorPSM2: false,
        isPanelPSM2: false,
    });

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

        const route_path =
            psmType === "PSM1"
                ? "coordinator.PSM1.panels.store"
                : "coordinator.PSM2.panels.store";

        router.post(route(route_path), data, {
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
                    name: "",
                    total_weight: "",
                    psmTypee: psmType,
                    isSupervisorPSM1: false,
                    isPanelPSM1: false,
                    isArchivePSM1: false,
                    isSupervisorPSM2: false,
                    isPanelPSM2: false,
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
                    <h2 className="text-lg font-semibold">Add Rubric</h2>
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
                    <form id="PSM1AddRubricForm" onSubmit={handleSubmit}>
                        <div className="p-4 border rounded border-gray-300 my-3 mx-2">
                            <h3 className="font-medium text-[#808080] mb-3">
                                Rubric Information
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
                                    Total Weight:
                                </label>
                                <input
                                    title="Total Weight"
                                    type="number"
                                    name="total_weight"
                                    value={data.total_weight}
                                    onChange={handleChange}
                                    className="col-span-4 border border-gray-300 rounded p-2 w-full"
                                    required
                                />
                                {errors.total_weight && (
                                    <p className="text-red-500 col-start-2 col-span-4">
                                        {errors.total_weight}
                                    </p>
                                )}
                            </div>
                        </div>

                        <div className="p-4 border rounded border-gray-300 my-3 mx-2">
                            <h3 className="font-medium text-[#808080] mb-3">
                                Role Selection
                            </h3>
                            {psmType === "PSM1" ? (
                                <div className="space-y-4">
                                    <div className="flex items-center">
                                        <label className="font-medium text-gray-700 w-25">
                                            Supervisor:
                                        </label>
                                        <input
                                            title="SupervisorPSM1"
                                            id="isSupervisorPSM1"
                                            type="checkbox"
                                            name="isSupervisorPSM1"
                                            checked={Boolean(
                                                data.isSupervisorPSM1
                                            )}
                                            onChange={handleChange}
                                            className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                        />
                                        {errors.isSupervisorPSM1 && (
                                            <p className="text-red-500 ml-2">
                                                {errors.isSupervisorPSM1}
                                            </p>
                                        )}
                                    </div>

                                    <div className="flex items-center">
                                        <label className="font-medium text-gray-700 w-25">
                                            Panel PSM1:
                                        </label>
                                        <input
                                            title="PanelPSM1"
                                            id="isPanelPSM1"
                                            type="checkbox"
                                            name="isPanelPSM1"
                                            checked={Boolean(data.isPanelPSM1)}
                                            onChange={handleChange}
                                            className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                        />
                                        {errors.isPanelPSM1 && (
                                            <p className="text-red-500 ml-2">
                                                {errors.isPanelPSM1}
                                            </p>
                                        )}
                                    </div>
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    <div className="flex items-center">
                                        <label className="font-medium text-gray-700 w-32">
                                            Supervisor:
                                        </label>
                                        <input
                                            title="SupervisorPSM2"
                                            id="isSupervisorPSM2"
                                            type="checkbox"
                                            name="isSupervisorPSM2"
                                            checked={Boolean(
                                                data.isSupervisorPSM2
                                            )}
                                            onChange={handleChange}
                                            className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                        />
                                        {errors.isSupervisorPSM1 && (
                                            <p className="text-red-500 ml-2">
                                                {errors.isSupervisorPSM2}
                                            </p>
                                        )}
                                    </div>

                                    <div className="flex items-center">
                                        <label className="font-medium text-gray-700 w-32">
                                            Panel PSM2:
                                        </label>
                                        <input
                                            title="PanelPSM2"
                                            id="isPanelPSM2"
                                            type="checkbox"
                                            name="isPanelPSM2"
                                            checked={Boolean(data.isPanelPSM2)}
                                            onChange={handleChange}
                                            className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                        />
                                        {errors.isPanelPSM1 && (
                                            <p className="text-red-500 ml-2">
                                                {errors.isPanelPSM2}
                                            </p>
                                        )}
                                    </div>
                                </div>
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
                    <button
                        type="submit"
                        form="PSM1AddRubricForm"
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

export default AddRubricModal;
