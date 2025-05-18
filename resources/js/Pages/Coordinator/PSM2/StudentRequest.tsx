import React, { useState, useEffect } from "react";
import { usePage, router } from "@inertiajs/react";
import {
    Pencil,
    Archive,
    ArchiveRestore,
    Trash,
    FileDown,
    CirclePlus,
} from "lucide-react";
import { route } from "ziggy-js";

interface Student {
    id: number;
    name: string;
    matric: string;
    course: string;
    title: string;
    project_area: string;
    project_type: string;
    sessionpsm: string;
    cohort: string;
    phone: string;
    email: string;
}

interface Flash {
    error?: string;
    success?: string;
    warning?: string[][];
}

export default function StudentRequest() {
    const { props } = usePage<{
        flash?: Flash;
        studentsDevelopment: Student[];
        studentResearch: Student[];
    }>();

    const studentsDevelopment = props.studentsDevelopment;
    const studentResearch = props.studentResearch;
    const [showStudentResearch, setStudentResearch] = useState(false);
    const [expandedRow, setExpandedRow] = useState<number | null>(null);

    // State for selected students
    const [selectedStudents, setSelectedStudents] = useState<number[]>([]);

    // State for pagination & search
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    // Filter students based on search query
    const filteredStudents = (
        showStudentResearch ? studentResearch : studentsDevelopment
    ).filter(
        (student) =>
            student.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            student.matric.toLowerCase().includes(searchQuery.toLowerCase()) ||
            (student.title &&
                student.title
                    .toLowerCase()
                    .includes(searchQuery.toLowerCase())) ||
            (student.email &&
                student.email
                    .toLowerCase()
                    .includes(searchQuery.toLowerCase())) ||
            student.course.toLowerCase().includes(searchQuery.toLowerCase()) || // Search by course
            (student.sessionpsm &&
                student.sessionpsm
                    .toLowerCase()
                    .includes(searchQuery.toLowerCase())) // Search by session
    );

    // Calculate total pages after filtering
    const totalPages = Math.ceil(filteredStudents.length / rowsPerPage);

    // Paginate filtered students
    const paginatedStudents = filteredStudents.slice(
        (currentPage - 1) * rowsPerPage,
        currentPage * rowsPerPage
    );

    // Update selectAll state based on current page selections
    useEffect(() => {
        if (paginatedStudents.length > 0) {
            const allCurrentPageSelected = paginatedStudents.every((student) =>
                selectedStudents.includes(student.id)
            );
        } else {
        }
    }, [selectedStudents, paginatedStudents]);

    // Reset selections when toggling archived view or changing search
    useEffect(() => {
        setSelectedStudents([]);
    }, [searchQuery]);

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

    const handleStatusUpdate = (
        studentId: number,
        status: "accepted" | "rejected"
    ) => {
        const route_path =
            status === "accepted"
                ? "coordinator.PSM2.sv.accept"
                : "coordinator.PSM2.sv.reject";
        router.post(route(route_path, studentId));
    };

    return (
        <div className="min-h-screen bg-gray-100 flex justify-center w-full pb-6">
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

            <div className="w-full">
                <div className="flex items-center justify-between">
                    <div className="mx-4 my-4">
                        <div className="flex border border-blue-400 rounded overflow-hidden font-semibold">
                            <button
                                type="button"
                                className={`p-1 px-3 transition  text-center ${
                                    !showStudentResearch
                                        ? "bg-blue-400 hover:bg-blue-500 transition text-white"
                                        : "bg-white hover:bg-gray-100 border-r "
                                }`}
                                onClick={() => {
                                    setStudentResearch(false);
                                }}
                            >
                                Development
                            </button>
                            <button
                                type="button"
                                className={`p-1 px-3 transition text-center ${
                                    showStudentResearch
                                        ? "bg-blue-400 hover:bg-blue-500 transition text-white"
                                        : "bg-white hover:bg-gray-100"
                                }`}
                                onClick={() => {
                                    setStudentResearch(true);
                                }}
                            >
                                Research
                            </button>
                        </div>
                    </div>
                </div>
                {/* Search Input */}
                <div className="flex justify-between mx-4 mt-3">
                    <div className="flex">
                        <label className="font-semibold">
                            Rows per page:
                            <select
                                className="ml-2 border border-gray-300 rounded p-1 bg-white"
                                value={rowsPerPage}
                                onChange={(e) => {
                                    setRowsPerPage(Number(e.target.value));
                                    setCurrentPage(1); // Reset to first page
                                }}
                            >
                                <option value={5}>5</option>
                                <option value={10}>10</option>
                                <option value={20}>20</option>
                                <option value={50}>50</option>
                            </select>
                        </label>
                    </div>

                    <input
                        type="text"
                        className="border border-gray-300 rounded p-2 w-1/5 bg-white hover:border-[#6D2323]"
                        placeholder="Search "
                        value={searchQuery}
                        onChange={(e) => {
                            setSearchQuery(e.target.value);
                            setCurrentPage(1); // Reset to first page on search
                        }}
                    />
                </div>

                {/* Table */}
                <table className="w-full border-collapse border-t border-b border-gray-300 mt-3">
                    <thead className="bg-gray-200">
                        <tr>
                            <th className="px-4 py-2 border-b border-gray-300">
                                No
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                Course
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                Matric No
                            </th>
                            <th className="px-4 py-2 text-left border-b border-gray-300">
                                Name
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {paginatedStudents.map((student, index) => (
                            <React.Fragment key={student.id}>
                                <tr
                                    className={`text-center bg-white hover:bg-gray-100 border-b border-gray-300 ${
                                        selectedStudents.includes(student.id)
                                            ? "bg-blue-50"
                                            : ""
                                    }`}
                                >
                                    <td
                                        className="px-4 py-2 cursor-pointer"
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === student.id
                                                    ? null
                                                    : student.id
                                            )
                                        }
                                    >
                                        {index +
                                            1 +
                                            (currentPage - 1) * rowsPerPage}
                                    </td>
                                    <td
                                        className="px-4 py-2 cursor-pointer"
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === student.id
                                                    ? null
                                                    : student.id
                                            )
                                        }
                                    >
                                        {student.course}
                                    </td>
                                    <td
                                        className="px-4 py-2 cursor-pointer"
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === student.id
                                                    ? null
                                                    : student.id
                                            )
                                        }
                                    >
                                        {student.matric}
                                    </td>
                                    <td
                                        className="px-4 py-2 text-left cursor-pointer"
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === student.id
                                                    ? null
                                                    : student.id
                                            )
                                        }
                                    >
                                        {student.name}
                                    </td>
                                    <td className="px-4 py-2">
                                        <div className="flex justify-center space-x-2">
                                            <button
                                                type="button"
                                                className="px-3 py-1 bg-green-400 text-white text-sm rounded hover:bg-green-500 transition"
                                                onClick={() =>
                                                    handleStatusUpdate(
                                                        student.id,
                                                        "accepted"
                                                    )
                                                }
                                            >
                                                Accept
                                            </button>
                                            <button
                                                type="button"
                                                className="px-3 py-1 bg-red-400 text-white text-sm rounded hover:bg-red-500 transition"
                                                onClick={() =>
                                                    handleStatusUpdate(
                                                        student.id,
                                                        "rejected"
                                                    )
                                                }
                                            >
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                {expandedRow === student.id && (
                                    <tr className="bg-gray-50 border-b border-gray-300">
                                        <td
                                            colSpan={4}
                                            className="px-4 py-2 text-left"
                                        >
                                            <div className="grid grid-cols-7 gap-4">
                                                <div className="col-span-4">
                                                    <strong>
                                                        Project Type:
                                                    </strong>{" "}
                                                    {student.project_type}{" "}
                                                    <br />
                                                    <strong>
                                                        Project Area:
                                                    </strong>{" "}
                                                    {student.project_area}{" "}
                                                    <br />
                                                    <strong>
                                                        Project Title:
                                                    </strong>{" "}
                                                    {student.title} <br />
                                                </div>

                                                <div className="col-span-3">
                                                    <strong>Email:</strong>{" "}
                                                    {student.email} <br />
                                                    <strong>
                                                        Cohort:
                                                    </strong>{" "}
                                                    {student.cohort} <br />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                )}
                            </React.Fragment>
                        ))}
                    </tbody>
                </table>

                {/* Pagination Controls */}
                <div className="flex justify-center space-x-2 items-center mt-4">
                    <button
                        type="button"
                        className="px-3 py-1 bg-white rounded disabled:opacity-50 hover:bg-gray-100 transition border border-gray-300"
                        disabled={currentPage === 1}
                        onClick={() => setCurrentPage((prev) => prev - 1)}
                    >
                        Prev
                    </button>

                    <span>
                        Page {currentPage} of {totalPages}
                    </span>

                    <button
                        type="button"
                        className="px-3 py-1 bg-white rounded disabled:opacity-50 hover:bg-gray-100 transition border border-gray-300"
                        disabled={currentPage === totalPages}
                        onClick={() => setCurrentPage((prev) => prev + 1)}
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    );
}
