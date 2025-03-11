import React, { useState, useEffect } from "react";
import { usePage, router } from "@inertiajs/react";
import { Pencil, Archive, ArchiveRestore, Trash, FileDown, CirclePlus } from "lucide-react";
import { route } from "ziggy-js";
import AddStudentModal from "../../../Components/AddStudentModal";
import EditStudentModal from "../../../Components/EditStudentModal";

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
    sv_name?: string; // Supervisor Name
    panel_name?: string; // Panel 1 Name
    panel2_name?: string; // Panel 2 Name
    panel_proposal_name?: string; // Proposal Panel Name
}

interface Flash {
    error?: string;
    success?: string;
}

export default function ListStudents() {
    const { props } = usePage<{
        students: Student[];
        archivedStudents: Student[];
        flash?: Flash;
    }>();

    const students = props.students;
    const archivedStudents = props.archivedStudents;

    const [showArchived, setShowArchived] = useState(false);
    const [expandedRow, setExpandedRow] = useState<number | null>(null);
    const [isAddModalOpen, setIsAddModalOpen] = useState(false);
    const [isEditModalOpen, setIsEditModalOpen] = useState(false);
    const [selectedStudent, setSelectedStudent] = useState<Student | null>(
        null
    );

    const handleArchive = (id: number) => {
        router.post(
            route("coordinator.PSM1.students.archive", id),
            {},
            { preserveScroll: true }
        );
    };

    const handleRestore = (id: number) => {
        router.post(
            route("coordinator.PSM1.students.restore", id),
            {},
            { preserveScroll: true }
        );
    };

    const handleDelete = (id: number) => {
        router.post(
            route("coordinator.PSM1.students.delete", id),
            {},
            { preserveScroll: true }
        );
    };

    // State for pagination & search
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    // Filter students based on search query
    const filteredStudents = (
        showArchived ? archivedStudents : students
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
                        <div className="flex border rounded overflow-hidden font-semibold">
                            <button
                                type="button"
                                className={`p-1 px-3 transition  text-center ${
                                    !showArchived
                                        ? "bg-[#6D2323] hover:bg-[#5a1d1d] transition text-white"
                                        : "bg-white hover:bg-gray-100 border-r "
                                }`}
                                onClick={() => setShowArchived(false)}
                            >
                                Active
                            </button>
                            <button
                                type="button"
                                className={`p-1 px-3 transition text-center ${
                                    showArchived
                                        ? "bg-[#6D2323] hover:bg-[#5a1d1d] transition text-white"
                                        : "bg-white hover:bg-gray-100"
                                }`}
                                onClick={() => setShowArchived(true)}
                            >
                                Archived
                            </button>
                        </div>
                    </div>

                    {/* <h1 className="text-center font-semibold text-3xl text-[#6D2323]">
                        List of PSM1 Students
                    </h1> */}

                    <div className="flex">
                        <button
                            type="button"
                            className="p-2 px-3 bg-[#6D2323] hover:bg-[#5a1d1d] transition text-white rounded my-4 font-semibold"
                            onClick={() => setIsAddModalOpen(true)}
                        >
                           <div className="flex">
                                <CirclePlus className="mr-2"/> 
                                Add Students
                            </div>
                        </button>
                        <button
                            type="button"
                            className="p-2 px-3 bg-[#6D2323] hover:bg-[#5a1d1d] transition text-white rounded ml-2 mr-4 my-4 font-semibold"
                        >
                            <div className="flex">
                                <FileDown className="mr-2"/> 
                                Import Students
                            </div>
                        </button>
                    </div>
                </div>

                {/* Search Input */}
                <div className="flex justify-between mx-4 my-2">
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

                    {/* Row Selection Dropdown */}
                </div>

                {/* Table */}
                <table className="w-full border-collapse border-t border-b border-gray-300">
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
                            <th className="px-4 py-2 text-left border-b border-gray-300">
                                Project Title
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                Session
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {paginatedStudents.map((student, index) => (
                            <React.Fragment key={student.id}>
                                <tr
                                    className="text-center bg-white hover:bg-gray-100 border-b border-gray-300 cursor-pointer"
                                    onClick={() =>
                                        setExpandedRow(
                                            expandedRow === student.id
                                                ? null
                                                : student.id
                                        )
                                    }
                                >
                                    <td className="px-4 py-2">
                                        {index +
                                            1 +
                                            (currentPage - 1) * rowsPerPage}
                                    </td>
                                    <td className="px-4 py-2">
                                        {student.course}
                                    </td>
                                    <td className="px-4 py-2">
                                        {student.matric}
                                    </td>
                                    <td className="px-4 py-2 text-left">
                                        {student.name}
                                    </td>
                                    <td className="px-4 py-2 text-left">
                                        {student.title}
                                    </td>
                                    <td className="px-4 py-2">
                                        {student.sessionpsm}
                                    </td>
                                    <td className="px-4 py-2">
                                        <div className="flex items-center justify-center space-x-2">
                                            <button
                                                type="button"
                                                className="p-1 text-blue-600 hover:text-blue-800 transition"
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    setSelectedStudent(student);
                                                    setIsEditModalOpen(true);
                                                }}
                                                title="Edit Student"
                                            >
                                                <Pencil
                                                    size={20}
                                                    className="transition-transform duration-200 hover:scale-125"
                                                />
                                            </button>
                                            {!showArchived ? (
                                                <>
                                                    <button
                                                        type="button"
                                                        className="p-1 text-red-600 hover:text-red-800 transition"
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            handleArchive(
                                                                student.id
                                                            );
                                                        }}
                                                        title="Archive Student"
                                                    >
                                                        <Archive
                                                            size={20}
                                                            className="transition-transform duration-200 hover:scale-125"
                                                        />
                                                    </button>
                                                </>
                                            ) : (
                                                <div className="flex">
                                                    <button
                                                        type="button"
                                                        className="p-1 text-green-600 hover:text-green-800 transition"
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            handleRestore(
                                                                student.id
                                                            );
                                                        }}
                                                        title="Restore Student"
                                                    >
                                                        <ArchiveRestore
                                                            size={20}
                                                            className="transition-transform duration-200 hover:scale-125"
                                                        />
                                                    </button>

                                                    <button
                                                        type="button"
                                                        className="p-1 text-green-600 hover:text-green-800 transition pl-2"
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            handleDelete(
                                                                student.id
                                                            );
                                                        }}
                                                        title="Delete Student"
                                                    >
                                                        <Trash
                                                            size={20}
                                                            className="transition-transform duration-200 hover:scale-125"
                                                        />
                                                    </button>
                                                </div>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                                {expandedRow === student.id && (
                                <tr className="bg-gray-50 border-b border-gray-300">
                                    <td colSpan={7} className="px-4 py-2 text-left">
                                        <div className="grid grid-cols-7 gap-4">
                                            <div className="col-span-2">
                                                <strong>Project Type:</strong> {student.project_type} <br />
                                                <strong>Project Area:</strong> {student.project_area} <br />
                                                <strong>Supervisor:</strong> {student.sv_name || "N/A"} <br />
                                            </div>
                                            <div className="col-span-2">
                                                <strong>Panel Proposal:</strong> {student.panel_proposal_name || "N/A"} <br />
                                                <strong>Panel 1:</strong> {student.panel_name || "N/A"} <br />
                                                <strong>Panel 2:</strong> {student.panel2_name || "N/A"} <br />
                                            </div>
                                            <div className="col-span-3">
                                                <strong>Email:</strong> {student.email} <br />
                                                <strong>Cohort:</strong> {student.cohort} <br />
                                                <strong>Session:</strong> {student.sessionpsm} <br />
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

            <AddStudentModal
                isOpen={isAddModalOpen}
                onClose={() => setIsAddModalOpen(false)}
            />

            <EditStudentModal
                isOpen={isEditModalOpen}
                onClose={() => {
                    setIsEditModalOpen(false);
                    setSelectedStudent(null);
                }}
                student={selectedStudent}
            />
        </div>
    );
}
