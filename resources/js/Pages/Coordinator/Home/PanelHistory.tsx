import React, { useState, useEffect } from "react";
import { usePage, router } from "@inertiajs/react";
import {
    Pencil,
    Archive,
    ArchiveRestore,
    Trash,
    FileDown,
    CirclePlus,
    Check,
    Search,
} from "lucide-react";
import { route } from "ziggy-js";
import PanelHistoryModal from "../../../Components/PanelHistoryModal";

interface Supervisor {
    id: number;
    name: string;
    matricNo: string;
    panel_histories: PanelHistory[]; 
}

interface PanelHistory {
    id: number;
    project_type: string;
    project_area: string;
}

interface Flash {
    error?: string;
    success?: string;
    warning?: string[][];
}

export default function PanelHistory() {
    const { props } = usePage<{
        users: Supervisor[];
        flash?: Flash;
    }>();

    const supervisor = props.users ?? [];
    const [selectedSupervisor, setSelectedSupervisor] = useState<{
        panelHistories: PanelHistory[] | null;
        name: string | null;
    } | null>(null);
    const [isModalOpen, setIsModalOpen] = useState(false);

    const handleOpenModal = (supervisor: Supervisor) => {
        setSelectedSupervisor({
            panelHistories: supervisor.panel_histories,
            name: supervisor.name
        });
        setIsModalOpen(true);
    };

    // State for pagination & search
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    // Filter panels based on search query
    const filteredSupervisor = supervisor.filter(
        (supervisor) =>
            supervisor.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            supervisor.matricNo
                .toLowerCase()
                .includes(searchQuery.toLowerCase())
        // Search by session
    );

    // Calculate total pages after filtering
    const totalPages = Math.ceil(filteredSupervisor.length / rowsPerPage);

    // Paginate filtered panels
    const paginatedSupervisor = filteredSupervisor.slice(
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

    useEffect(() => {
        console.log("Fetched users:", props.users);
    }, [props.users]);

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
                {/* Search Input */}
                <div className="flex justify-between mx-4 mt-2">
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
                                Matric No
                            </th>
                            <th className="text-left px-4 py-2 border-b border-gray-300">
                                Name
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {paginatedSupervisor.map((supervisor, index) => (
                            <React.Fragment key={supervisor.id}>
                                <tr
                                    className={
                                        "text-center bg-white hover:bg-gray-100 border-b border-gray-300 "
                                    }
                                >
                                    <td className="px-4 py-2 cursor-pointer">
                                        {index +
                                            1 +
                                            (currentPage - 1) * rowsPerPage}
                                    </td>
                                    <td className="px-4 py-2 cursor-pointer">
                                        {supervisor.matricNo}
                                    </td>
                                    <td className="px-4 py-2 cursor-pointer text-left max-w-[200px]">
                                        {supervisor.name}
                                    </td>
                                    <td>
                                        <button
                                            type="button"
                                            title="Assign Student"
                                            className="p-2 px-3 bg-blue-400 text-white rounded hover:bg-blue-500 transition my-2 ml-2 font-semibold"
                                            onClick={() => {
                                                handleOpenModal(supervisor)
                                                // console.log(supervisor.panel_histories)
                                            }}
                                        >
                                            <div className="flex">
                                                <Search  />
                                            </div>
                                        </button>
                                    </td>
                                </tr>
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

            {/* Assign student supervisor Modal */}

            {isModalOpen && selectedSupervisor !== null && (
                <PanelHistoryModal
                    isOpen={isModalOpen}
                    panelHistory={selectedSupervisor.panelHistories}
                    panelName={selectedSupervisor.name}
                    onClose={() => setIsModalOpen(false)}
                />
            )}
        </div>
    );
}
