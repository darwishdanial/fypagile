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
    X,
    Bot
} from "lucide-react";
import { route } from "ziggy-js";
import AddPanelModal from "../../../Components/AddPanelModal";
import EditPanelModal from "../../../Components/EditPanelModal";
import ImportPanelsModal from "../../../Components/ImportPanelsModal";
import ImportErrorModal from "../../../Components/ImportErrorModal";
import AssignPanelModal from "../../../Components/AssignPanelModal";

interface Panel {
    id: number;
    matricNo: string;
    name: string;
    username: string;
    email: string;
}

interface Flash {
    error?: string;
    success?: string;
    warning?: string[][];
}

export default function AssignProposalPanel() {
    const { props } = usePage<{
        panel: Panel[];
        flash?: Flash;
    }>();

    const panel = props.panel ?? [];
    const [selectedPanel, setSelectedPanel] = useState<number | null>(null);
    const [isPanel1ModalOpen, setIsPanel1ModalOpen] = useState(false);
    const [isPanel2ModalOpen, setIsPanel2ModalOpen] = useState(false);

    const handleOpenPanel1Modal = (id: number) => {
        setSelectedPanel(id);
        setIsPanel1ModalOpen(true);
    };

    const handleOpenPanel2Modal = (id: number) => {
        setSelectedPanel(id);
        setIsPanel2ModalOpen(true);
    };

    // State for pagination & search
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    // Filter panels based on search query
    const filteredPanel = panel.filter(
        (panel) =>
            panel.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            panel.matricNo.toLowerCase().includes(searchQuery.toLowerCase()) ||
            panel.username.toLowerCase().includes(searchQuery.toLowerCase()) ||
            (panel.email &&
                panel.email.toLowerCase().includes(searchQuery.toLowerCase())) // Search by session
    );

    // Calculate total pages after filtering
    const totalPages = Math.ceil(filteredPanel.length / rowsPerPage);

    // Paginate filtered panels
    const paginatedPanel = filteredPanel.slice(
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

                    <div className="flex">

                        <button
                            type="button"
                            className="p-2 px-3 bg-blue-400 hover:bg-blue-500 transition text-white rounded  mr-2 font-semibold"
                            onClick={() => console.log("AI Panel")}
                            title="Suggest Panels using AI"
                        >
                            <div className="flex">
                                <Bot className="mr-2" />
                                AI Suggestions
                            </div>
                        </button>

                        <input
                        type="text"
                        className="border border-gray-300 rounded p-2  bg-white hover:border-[#6D2323]"
                        placeholder="Search "
                        value={searchQuery}
                        onChange={(e) => {
                            setSearchQuery(e.target.value);
                            setCurrentPage(1); // Reset to first page on search
                        }}
                    />
                    </div>

                    
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
                            <th className="px-4 py-2 text-left border-b border-gray-300">
                                Username
                            </th>
                            <th className="text-left px-4 py-2 border-b border-gray-300">
                                Email
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {paginatedPanel.map((panel, index) => (
                            <React.Fragment key={panel.id}>
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
                                        {panel.matricNo}
                                    </td>
                                    <td className="px-4 py-2 cursor-pointer text-left max-w-[200px]">
                                        {panel.name}
                                    </td>
                                    <td className="px-4 py-2 text-left cursor-pointer break-words max-w-[200px]">
                                        {panel.username}
                                    </td>
                                    <td className="px-4 py-2 text-left cursor-pointer break-words max-w-[250px]">
                                        {panel.email}
                                    </td>

                                    <td>
                                        <div className="flex justify-center space-x-2">
                                            <button
                                                type="button"
                                                title="Assign Student"
                                                className="p-2 px-3 bg-blue-400 text-white rounded hover:bg-blue-500 transition my-2 ml-2 font-semibold"
                                                onClick={() =>
                                                    handleOpenPanel1Modal(
                                                        panel.id
                                                    )
                                                }
                                            >
                                                <div className="flex">
                                                    <CirclePlus className="mr-2" />
                                                    Panel 1
                                                </div>
                                            </button>

                                            <button
                                                type="button"
                                                title="Assign Student"
                                                className="p-2 px-3 bg-blue-400 text-white rounded hover:bg-blue-500 transition my-2 ml-2 font-semibold"
                                                onClick={() =>
                                                    handleOpenPanel2Modal(
                                                        panel.id
                                                    )
                                                }
                                            >
                                                <div className="flex">
                                                    <CirclePlus className="mr-2" />
                                                    Panel 2
                                                </div>
                                            </button>
                                        </div>
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

            {isPanel1ModalOpen && selectedPanel !== null && (
                <AssignPanelModal
                    isOpen={isPanel1ModalOpen}
                    panelId={selectedPanel}
                    panelType="PSM1ProposalPanel1"
                    onClose={() => setIsPanel1ModalOpen(false)}
                />
            )}

            {isPanel2ModalOpen && selectedPanel !== null && (
                <AssignPanelModal
                    isOpen={isPanel2ModalOpen}
                    panelId={selectedPanel}
                    panelType="PSM1ProposalPanel2"
                    onClose={() => setIsPanel2ModalOpen(false)}
                />
            )}
        </div>
    );
}
