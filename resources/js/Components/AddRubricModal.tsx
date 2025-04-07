import React, { useState, useEffect } from "react";
import { router, useForm, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
import { X } from "lucide-react";

interface AddRubricModalProps {
    isOpen: boolean;
    onClose: () => void;
    psmType: string;
    rubric: string;
}

const AddRubricModal: React.FC<AddRubricModalProps> = ({
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
        name: "",
        total_weight: "",
        PSMType: psmType,
        isEnable: false,
        roleType: "", // Single role selection
        rubricType: rubric,
        session: "",
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
        } else if (type === "radio") {
            setData((prev) => ({
                ...prev,
                [name]: value,
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

        const roleTypeValue =
            data.roleType === "coordinator"
                ? 1
                : data.roleType === "panel"
                ? 2
                : data.roleType === "supervisor"
                ? 3
                : null;

        // Transform the roleType into the expected format for the backend
        const transformedData = {
            ...data,
            roleType: roleTypeValue,
            isResearch: data.rubricType === "research",
            isDevelopment: data.rubricType === "development",
        };

        const route_path =
            psmType === "PSM1"
                ? "coordinator.PSM1.evaluationRubric.store"
                : "coordinator.PSM2.evaluationRubric.store";

        router.post(route(route_path), transformedData, {
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
                    PSMType: psmType,
                    isEnable: false,
                    roleType: "",
                    rubricType: "",
                    session: "",
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

                <div className="overflow-y-auto max-h-[80vh]">
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
                                    type="decimal"
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

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Session:
                                </label>
                                <input
                                    title="Session"
                                    type="string"
                                    name="session"
                                    value={data.session}
                                    onChange={handleChange}
                                    className="col-span-4 border border-gray-300 rounded p-2 w-full"
                                    required
                                />
                                {errors.session && (
                                    <p className="text-red-500 col-start-2 col-span-4">
                                        {errors.session}
                                    </p>
                                )}
                            </div>

                            <div className="flex items-center mb-4">
                                <label className="font-medium text-gray-700 w-25">
                                    Enable:
                                </label>
                                <input
                                    title="Enable Rubric"
                                    id="isEnable"
                                    type="checkbox"
                                    name="isEnable"
                                    checked={Boolean(data.isEnable)}
                                    onChange={handleChange}
                                    className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                />
                                {errors.isEnable && (
                                    <p className="text-red-500 ml-2">
                                        {errors.isEnable}
                                    </p>
                                )}
                            </div>

                            {/* <div className="flex items-center">
                                <label className="font-medium text-gray-700 w-25">
                                    Type:
                                </label>
                                //research radio button //development radio
                                button
                            </div> */}
                        </div>

                        {/* <div className="p-4 border rounded border-gray-300 my-3 mx-2">
                            <h3 className="font-medium text-[#808080] mb-3">
                                Rubric Type Selection (Select One)
                            </h3>

                            <div className="space-y-4">
                                <div className="flex items-center">
                                    <label className="font-medium text-gray-700 w-32">
                                        Research:
                                    </label>
                                    <input
                                        title="Research"
                                        id="research"
                                        type="radio"
                                        name="rubricType"
                                        value="research"
                                        checked={data.rubricType === "research"}
                                        onChange={handleChange}
                                        className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-full focus:ring-blue-500"
                                    />
                                </div>

                                <div className="flex items-center">
                                    <label className="font-medium text-gray-700 w-32">
                                        Development:
                                    </label>
                                    <input
                                        title="Development"
                                        id="development"
                                        type="radio"
                                        name="rubricType"
                                        value="development"
                                        checked={
                                            data.rubricType === "development"
                                        }
                                        onChange={handleChange}
                                        className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-full focus:ring-blue-500"
                                    />
                                </div>
                            </div>

                            {errors.rubricType && (
                                <p className="text-red-500 mt-2">
                                    {errors.rubricType}
                                </p>
                            )}
                        </div> */}

                        <div className="p-4 border rounded border-gray-300 my-3 mx-2">
                            <h3 className="font-medium text-[#808080] mb-3">
                                Role Selection (Select One)
                            </h3>
                            <div className="space-y-4">
                                <div className="flex items-center">
                                    <label className="font-medium text-gray-700 w-25">
                                        Coordinator:
                                    </label>
                                    <input
                                        title="Coordinator"
                                        id="coordinator"
                                        type="radio"
                                        name="roleType"
                                        value="coordinator"
                                        checked={
                                            data.roleType === "coordinator"
                                        }
                                        onChange={handleChange}
                                        className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-full focus:ring-blue-500"
                                    />
                                </div>

                                <div className="flex items-center">
                                    <label className="font-medium text-gray-700 w-25">
                                        Supervisor:
                                    </label>
                                    <input
                                        title="Supervisor"
                                        id="supervisor"
                                        type="radio"
                                        name="roleType"
                                        value="supervisor"
                                        checked={data.roleType === "supervisor"}
                                        onChange={handleChange}
                                        className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-full focus:ring-blue-500"
                                    />
                                </div>

                                <div className="flex items-center">
                                    <label className="font-medium text-gray-700 w-25">
                                        Panel:
                                    </label>
                                    <input
                                        title="Panel"
                                        id="panel"
                                        type="radio"
                                        name="roleType"
                                        value="panel"
                                        checked={data.roleType === "panel"}
                                        onChange={handleChange}
                                        className="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-full focus:ring-blue-500"
                                    />
                                </div>
                            </div>

                            {errors.roleType && (
                                <p className="text-red-500 mt-2">
                                    {errors.roleType}
                                </p>
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
