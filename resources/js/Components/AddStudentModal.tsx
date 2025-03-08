import React, { useState, useEffect } from "react";
import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import { X } from "lucide-react";

interface AddStudentModalProps {
    isOpen: boolean;
    onClose: () => void;
}

const AddStudentModal: React.FC<AddStudentModalProps> = ({
    isOpen,
    onClose,
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

    const [formData, setFormData] = useState({
        name: "",
        matric: "",
        course: "",
        project_type: "",
        project_area: "",
        title: "",
        sessionpsm: "",
    });

    const handleChange = (
        e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
    ) => {
        const { name, value } = e.target;
        setFormData((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        router.post(route("coordinator.PSM1.students.store"), formData, {
            onSuccess: () => {
                onClose();
                // Reset form
                setFormData({
                    name: "",
                    matric: "",
                    course: "",
                    project_type: "",
                    project_area: "",
                    title: "",
                    sessionpsm: "",
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
                className="bg-white rounded-lg w-full max-w-lg shadow-xl"
                onClick={(e) => e.stopPropagation()}
            >
                <div className="flex justify-between items-center py-2 px-4">
                    <h2 className="text-lg font-semibold">Add Student</h2>
                    <button
                        title="close"
                        type="button"
                        onClick={onClose}
                        className="text-gray-500 hover:text-gray-700"
                    >
                        <X size={20} />
                    </button>
                </div>

                <hr className="border-t-1 border-gray-300"></hr>

                <div className="overflow-y-auto  max-h-[80vh]">
                    <form id="PSM1AddStudentForm" onSubmit={handleSubmit}>
                        <div className="p-4 border rounded border-gray-300 my-3 mx-2">
                            <h3 className="font-medium text-[#808080] mb-3">
                                Student Information
                            </h3>

                            <div className="flex mb-4 items-center justify-between">
                                <label className="block mb-1 font-medium">
                                    Name:
                                </label>
                                <input
                                    title="Name"
                                    type="text"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                            </div>

                            <div className="flex mb-4 items-center justify-between">
                                <label className="block mb-1 font-medium">
                                    Matric no:
                                </label>
                                <input
                                    title="Matric no"
                                    type="text"
                                    name="matric"
                                    value={formData.matric}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                            </div>

                            <div className="flex items-center justify-between">
                                <label className="block mb-1 font-medium">
                                    Course:
                                </label>
                                <input
                                    title="Course"
                                    type="text"
                                    name="course"
                                    value={formData.course}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                            </div>
                        </div>

                        <div className="p-4 border rounded border-gray-300 m-2 my-3">
                            <h3 className="font-medium text-[#808080] mb-3">
                                PSM Information
                            </h3>

                            <div className="flex mb-4 items-center justify-between">
                                <label className="block mb-1 font-medium">
                                    Project Type:
                                </label>
                                <select
                                    title="Project Type"
                                    name="project_type"
                                    value={formData.project_type}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                >
                                    <option value="">
                                        Select Project Type
                                    </option>
                                    <option value="Development">
                                        Development
                                    </option>
                                    <option value="Research">Research</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div className="flex mb-4 items-center justify-between">
                                <label className="block mb-1 font-medium">
                                    Project Area:
                                </label>
                                <input
                                    title="Project Area"
                                    type="text"
                                    name="project_area"
                                    value={formData.project_area}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                            </div>

                            <div className="flex mb-4 items-center justify-between">
                                <label className="block mb-1 font-medium">
                                    Project Title:
                                </label>
                                <input
                                    title="Project Title"
                                    type="text"
                                    name="title"
                                    value={formData.title}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                            </div>

                            <div className="flex items-center justify-between">
                                <label className="block mb-1 font-medium">
                                    Session PSM:
                                </label>
                                <input
                                    title="Session PSM"
                                    type="text"
                                    name="sessionpsm"
                                    value={formData.sessionpsm}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
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
                        form="PSM1AddStudentForm"
                        className="px-4 py-2 bg-[#6D2323] text-white rounded hover:bg-[#5a1d1d] transition"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    );
};

export default AddStudentModal;
