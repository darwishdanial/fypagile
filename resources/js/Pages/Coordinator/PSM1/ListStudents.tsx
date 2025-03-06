import React, { useState } from "react";
import { usePage, router } from "@inertiajs/react";
import { Pencil, Archive, ArchiveRestore } from "lucide-react";
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
    sv_name?: string; // Supervisor Name
    panel_name?: string; // Panel 1 Name
    panel2_name?: string; // Panel 2 Name
}

export default function ListStudents() {
    const { props } = usePage<{
        props: {
            students: Student[];
            archivedStudents: Student[];
            flash?: { success?: string };
        };
    }>();
    const students = props.students;
    const archivedStudents = props.archivedStudents;

    const [showArchived, setShowArchived] = useState(false);

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

    return (
        <div className="min-h-screen bg-gray-100 flex justify-center">
            <div className="w-full bg-gray-100 shadow-lg pb-6">
                {/* Flash Message */}

                <div className="flex items-center justify-between">
                    <div className="mx-4 my-4">
                        <div className="flex border rounded overflow-hidden font-semibold">
                            <button
                                type="button"
                                className={`p-1 px-3 transition  text-center ${
                                    !showArchived
                                        ? "bg-[#6D2323] text-white"
                                        : "bg-white border-r "
                                }`}
                                onClick={() => setShowArchived(false)}
                            >
                                Active
                            </button>
                            <button
                                type="button"
                                className={`p-1 px-3 transition text-center ${
                                    showArchived
                                        ? "bg-[#6D2323] text-white"
                                        : "bg-white "
                                }`}
                                onClick={() => setShowArchived(true)}
                            >
                                Archived
                            </button>
                        </div>
                    </div>

                    <h1 className="text-center font-semibold text-3xl text-[#6D2323]">
                        List of PSM1 Students
                    </h1>

                    <button
                        type="button"
                        className="p-2 px-3 bg-[#6D2323] text-white rounded mx-4 my-4 font-semibold"
                    >
                        + Import Students
                    </button>
                </div>

                {/* Search Input */}
                <div className="flex justify-between mx-4 my-4">
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
                        className="border border-gray-300 rounded p-2 w-1/5 bg-white"
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
                <table className="w-full border-collapse border border-gray-300">
                    <thead className="bg-gray-200">
                        <tr>
                            <th className="border border-gray-300 px-4 py-2">
                                No
                            </th>
                            <th className="border border-gray-300 px-4 py-2">
                                Course
                            </th>
                            <th className="border border-gray-300 px-4 py-2">
                                Matric No
                            </th>
                            <th className="border border-gray-300 px-4 py-2 text-left">
                                Name
                            </th>
                            <th className="border border-gray-300 px-4 py-2 text-left">
                                Project Title
                            </th>
                            <th className="border border-gray-300 px-4 py-2">
                                Session
                            </th>
                            <th className="border border-gray-300 px-4 py-2">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {paginatedStudents.map((student, index) => (
                            <tr
                                key={student.id}
                                className="text-center bg-white"
                            >
                                <td className="border border-gray-300 px-4 py-2">
                                    {index +
                                        1 +
                                        (currentPage - 1) * rowsPerPage}
                                </td>
                                <td className="border border-gray-300 px-4 py-2">
                                    {student.course}
                                </td>
                                <td className="border border-gray-300 px-4 py-2">
                                    {student.matric}
                                </td>
                                <td className="border border-gray-300 px-4 py-2 text-left">
                                    {student.name}
                                </td>
                                <td className="border border-gray-300 px-4 py-2 text-left">
                                    {student.title}
                                </td>
                                <td className="border border-gray-300 px-4 py-2">
                                    {student.sessionpsm}
                                </td>
                                <td className="border border-gray-300 px-4 py-2">
                                    <div className="flex items-center justify-center space-x-2">
                                        <button
                                            type="button"
                                            className="p-1 text-blue-600 hover:text-blue-800 transition"
                                            onClick={() =>
                                                console.log("Edit", student.id)
                                            }
                                            title="Edit Student"
                                        >
                                            <Pencil size={20} />
                                        </button>
                                        {!showArchived ? (
                                            <>
                                                <button
                                                    type="button"
                                                    className="p-1 text-red-600 hover:text-red-800 transition"
                                                    onClick={() =>
                                                        handleArchive(
                                                            student.id
                                                        )
                                                    }
                                                    title="Archive Student"
                                                >
                                                    <Archive size={20} />
                                                </button>
                                            </>
                                        ) : (
                                            <button
                                                type="button"
                                                className="p-1 text-green-600 hover:text-green-800 transition"
                                                onClick={() =>
                                                    handleRestore(student.id)
                                                }
                                                title="Restore Student"
                                            >
                                                <ArchiveRestore size={20} />
                                            </button>
                                        )}
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>

                {/* Pagination Controls */}
                <div className="flex justify-center space-x-2 items-center mt-4">
                    <button
                        type="button"
                        className="px-3 py-1 bg-white rounded disabled:opacity-50 hover:bg-gray-300 transition border border-gray-300"
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
                        className="px-3 py-1 bg-white rounded disabled:opacity-50 hover:bg-gray-300 transition border border-gray-300"
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
