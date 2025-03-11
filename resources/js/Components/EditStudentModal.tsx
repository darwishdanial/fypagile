import React, { useState, useEffect } from "react";
import { router, useForm, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
import { X } from "lucide-react";

interface Student {
    id: number;
    name: string;
    matric: string;
    course: string;
    title: string;
    project_area: string;
    project_type: string;
    sessionpsm: string;
    cohort?: string;
    phone?: string;
    email?: string;
}

interface EditStudentModalProps {
    isOpen: boolean;
    onClose: () => void;
    student: Student | null;
}

const EditStudentModal: React.FC<EditStudentModalProps> = ({
    isOpen,
    onClose,
    student,
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

    const { data, setData, processing, reset } = useForm({
        name: student?.name || "",
        matric: student?.matric || "",
        course: student?.course || "",
        cohort: student?.cohort || "",
        phone: student?.phone || "",
        email: student?.email || "",
        project_type: student?.project_type || "",
        project_area: student?.project_area || "",
        title: student?.title || "",
        sessionpsm: student?.sessionpsm || "",
    });

    const { errors } = usePage().props

    // Update form data when student prop changes
    useEffect(() => {
        if (student) {
            setData({
                name: student.name || "",
                matric: student.matric || "",
                course: student.course || "",
                cohort: student.cohort || "",
                phone: student.phone || "",
                email: student.email || "",
                project_type: student.project_type || "",
                project_area: student.project_area || "",
                title: student.title || "",
                sessionpsm: student.sessionpsm || "",
            });
        }
    }, [student, setData]);

    const handleChange = (
        e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
    ) => {
        const { name, value } = e.target;
        setData((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (!student) return;

        router.put(
            route("coordinator.PSM1.students.update", student.id),
            data,
            {
                onError: (errors) => {
                    console.log(errors);
                },
                onSuccess: () => {
                    onClose();
                },
            }
        );
    };

    if (!isOpen || !student) return null;

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
                    <h2 className="text-lg font-semibold">Edit Student</h2>
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
                    <form id="PSM1EditStudentForm" onSubmit={handleSubmit}>
                        <div className="p-4 border rounded border-gray-300 my-3 mx-2">
                            <h3 className="font-medium text-[#808080] mb-3">
                                Student Information
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
                                    Matric no:
                                </label>

                                <input
                                    title="Matric no"
                                    type="text"
                                    name="matric"
                                    value={data.matric}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                                {errors.matric && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.matric}
                                    </p>
                                )}
                            </div>

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Cohort:
                                </label>
                                <input
                                    title="Cohort"
                                    type="text"
                                    name="cohort"
                                    value={data.cohort}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                                {errors.cohort && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.cohort}
                                    </p>
                                )}
                            </div>

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Phone:
                                </label>
                                <input
                                    title="Phone"
                                    type="text"
                                    name="phone"
                                    value={data.phone}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                                {errors.phone && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.phone}
                                    </p>
                                )}
                            </div>

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Email:
                                </label>
                                <input
                                    title="Email"
                                    type="text"
                                    name="email"
                                    value={data.email}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                                {errors.email && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.email}
                                    </p>
                                )}
                            </div>

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Course:
                                </label>
                                <input
                                    title="Course"
                                    type="text"
                                    name="course"
                                    value={data.course}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                                {errors.course && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.course}
                                    </p>
                                )}
                            </div>
                        </div>

                        <div className="p-4 border rounded border-gray-300 m-2 my-3">
                            <h3 className="font-medium text-[#808080] mb-3">
                                PSM Information
                            </h3>

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Project Type:
                                </label>
                                <select
                                    title="Project Type"
                                    name="project_type"
                                    value={data.project_type}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                >
                                    <option value="">
                                        Select Project Type
                                    </option>
                                    <option value="System Development">
                                        System Development
                                    </option>
                                    <option value="Research">Research</option>
                                </select>
                                {errors.project_type && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.project_type}
                                    </p>
                                )}
                            </div>

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Project Area:
                                </label>
                                <input
                                    title="Project Area"
                                    type="text"
                                    name="project_area"
                                    value={data.project_area}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                                {errors.project_area && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.project_area}
                                    </p>
                                )}
                            </div>

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Project Title:
                                </label>
                                <input
                                    title="Project Title"
                                    type="text"
                                    name="title"
                                    value={data.title}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                                {errors.title && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.title}
                                    </p>
                                )}
                            </div>

                            <div className="grid grid-cols-5 mb-4 items-center">
                                <label className="col-span-1 font-medium">
                                    Session PSM:
                                </label>
                                <input
                                    title="Session PSM"
                                    type="text"
                                    name="sessionpsm"
                                    value={data.sessionpsm}
                                    onChange={handleChange}
                                    className="w-90 border border-gray-300 rounded p-2"
                                    required
                                />
                                {errors.sessionpsm && (
                                    <p className="text-red-500 col-start-2 col-span-5">
                                        {errors.sessionpsm}
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
                        form="PSM1EditStudentForm"
                        disabled={processing}
                        className="px-4 py-2 bg-[#6D2323] text-white rounded hover:bg-[#5a1d1d] transition"
                    >
                        Update
                    </button>
                </div>
            </div>
        </div>
    );
};

export default EditStudentModal;
