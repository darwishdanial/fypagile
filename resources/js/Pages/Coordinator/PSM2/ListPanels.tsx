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
} from "lucide-react";
import { route } from "ziggy-js";
import AddPanelModal from "../../../Components/AddPanelModal";
import EditPanelModal from "../../../Components/EditPanelModal";
import ImportPanelsModal from "../../../Components/ImportPanelsModal";
import ImportErrorModal from "../../../Components/ImportErrorModal";

interface Panel {
    id: number;
    matricNo: string;
    name: string;
    role: number;
    username: string;
    email: string;
    isSupervisorPSM1: boolean;
    isProposalPanel: boolean;
    isPanelPSM1: boolean;
    isSupervisorPSM2: boolean;
    isPanelPSM2: boolean;
    students_sv_names?: string[];
    students_proposal_names?: string[];
    students_panel1_names?: string[];
    students_panel2_names?: string[];
}

interface Flash {
    error?: string;
    success?: string;
    warning?: string[][];
}

export default function ListPanels() {
    const { props } = usePage<{
        panels: Panel[];
        archivedPanels: Panel[];
        flash?: Flash;
    }>();

    const panels = props.panels ?? [];
    const archivedPanels = props.archivedPanels ?? [];
    const panelType = "PSM2";

    const [showArchived, setShowArchived] = useState(false);
    const [expandedRow, setExpandedRow] = useState<number | null>(null);
    const [isAddModalOpen, setIsAddModalOpen] = useState(false);
    const [isEditModalOpen, setIsEditModalOpen] = useState(false);
    const [isImportErrorModalOpen, setIsImportErrorModalOpen] = useState(false);
    const [selectedPanel, setSelectedPanel] = useState<Panel | null>(null);
    const [isImportModalOpen, setIsImoprtModalOpen] = useState(false);

    // State for selected panels
    const [selectedPanels, setSelectedPanels] = useState<number[]>([]);
    const [selectAll, setSelectAll] = useState(false);
    const [selectAllPages, setSelectAllPages] = useState(false);

    const handleArchive = (id: number) => {
        router.post(
            route("coordinator.PSM2.panels.archive", id),
            {},
            { preserveScroll: true }
        );
    };

    // Handle bulk archive of selected students
    const handleBulkArchive = () => {
        if (setSelectedPanels.length === 0) return;

        const isConfirmed = confirm(
            `Are you sure you want to archive ${selectedPanels.length} selected panels?`
        );

        if (isConfirmed) {
            router.post(
                route("coordinator.PSM2.panels.bulkArchive"),
                { ids: selectedPanels },
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        setSelectedPanels([]);
                        setSelectAll(false);
                        setSelectAllPages(false);
                    },
                }
            );
        }
    };

    const handleRestore = (id: number) => {
        router.post(
            route("coordinator.PSM2.panels.restore", id),
            {},
            { preserveScroll: true }
        );
    };

    const handleDelete = (id: number) => {
        const isConfirmed = confirm(
            "Are you sure you want to delete this panel? This action cannot be undone."
        );

        if (isConfirmed) {
            router.delete(route("coordinator.PSM2.panels.delete", id), {
                preserveScroll: true,
            });
        }
    };

    // State for pagination & search
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    // Filter panels based on search query
    const filteredPanels = (showArchived ? archivedPanels : panels).filter(
        (panel) =>
            panel.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            panel.matricNo.toLowerCase().includes(searchQuery.toLowerCase()) ||
            panel.username.toLowerCase().includes(searchQuery.toLowerCase()) ||
            (panel.email &&
                panel.email.toLowerCase().includes(searchQuery.toLowerCase())) // Search by session
    );

    // Calculate total pages after filtering
    const totalPages = Math.ceil(filteredPanels.length / rowsPerPage);

    // Paginate filtered panels
    const paginatedPanels = filteredPanels.slice(
        (currentPage - 1) * rowsPerPage,
        currentPage * rowsPerPage
    );

    // Handle select all on current page only
    const handleSelectAllOnPage = () => {
        if (selectAll) {
            // If currently all selected on page, deselect them
            const currentPageIds = paginatedPanels.map((panel) => panel.id);
            setSelectedPanels((prev) =>
                prev.filter((id) => !currentPageIds.includes(id))
            );
        } else {
            // Select all on current page (preserving other selections)
            const currentPageIds = paginatedPanels.map((panel) => panel.id);
            setSelectedPanels((prev) => {
                const newSelection = [...prev];
                currentPageIds.forEach((id) => {
                    if (!newSelection.includes(id)) {
                        newSelection.push(id);
                    }
                });
                return newSelection;
            });
        }
    };

    // Handle selecting all panels across all pages
    const handleSelectAllPages = () => {
        if (selectAllPages) {
            // Deselect all
            setSelectedPanels([]);
            setSelectAllPages(false);
            setSelectAll(false);
        } else {
            // Select all across all pages
            const allIds = filteredPanels.map((panel) => panel.id);
            setSelectedPanels(allIds);
            setSelectAllPages(true);
            setSelectAll(true);
        }
    };

    // Handle individual row selection
    const handleSelectRow = (id: number) => {
        setSelectedPanels((prev) => {
            if (prev.includes(id)) {
                return prev.filter((panelId) => panelId !== id);
            } else {
                return [...prev, id];
            }
        });
    };

    // Update selectAll state based on current page selections
    useEffect(() => {
        if (paginatedPanels.length > 0) {
            const allCurrentPageSelected = paginatedPanels.every((panel) =>
                selectedPanels.includes(panel.id)
            );
            setSelectAll(allCurrentPageSelected);
        } else {
            setSelectAll(false);
        }
    }, [selectedPanels, paginatedPanels]);

    // Reset selections when toggling archived view or changing search
    useEffect(() => {
        setSelectedPanels([]);
        setSelectAll(false);
        setSelectAllPages(false);
    }, [showArchived, searchQuery]);

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

        if (props.flash?.warning) {
            setIsImportErrorModalOpen(true);
        }

        if (props.flash?.success || props.flash?.error) {
            const timer = setTimeout(() => setFlashMessage(null), 3000); // Hide after 3s
            return () => clearTimeout(timer);
        }
    }, [props.flash]); // Run effect when flash message changes

    // Calculate selection statistics
    const totalActivePanels = filteredPanels.length;
    const selectedCount = selectedPanels.length;

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
                                    !showArchived
                                        ? "bg-blue-400 hover:bg-blue-500 transition text-white"
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
                                        ? "bg-blue-400 hover:bg-blue-500 transition text-white"
                                        : "bg-white hover:bg-gray-100"
                                }`}
                                onClick={() => setShowArchived(true)}
                            >
                                Archived
                            </button>
                        </div>
                    </div>

                    <div className="flex">
                        {/* Show bulk archive button when panels are selected */}
                        {selectedPanels.length > 0 && !showArchived && (
                            <button
                                type="button"
                                className="p-2 px-3bg-blue-400 hover:bg-blue-500 transition text-white rounded my-4 ml-2 font-semibold"
                                onClick={handleBulkArchive}
                                title="Archive Selected Panels"
                            >
                                <div className="flex">
                                    <Archive className="mr-2" />
                                    Archive Selected ({selectedPanels.length})
                                </div>
                            </button>
                        )}
                        <button
                            type="button"
                            className="p-2 px-3 bg-blue-400 hover:bg-blue-500 transition text-white rounded my-4 ml-2 font-semibold"
                            onClick={() => setIsAddModalOpen(true)}
                            title="Add Panel"
                        >
                            <div className="flex">
                                <CirclePlus className="mr-2" />
                                Add Panels
                            </div>
                        </button>
                        <button
                            type="button"
                            className="p-2 px-3 bg-blue-400 hover:bg-blue-500 transition text-white rounded ml-2 mr-4 my-4 font-semibold"
                            onClick={() => setIsImoprtModalOpen(true)}
                            title="Import Panels"
                        >
                            <div className="flex">
                                <FileDown className="mr-2" />
                                Import Panels
                            </div>
                        </button>
                    </div>
                </div>

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

                {/* Selection status and controls for all-pages selection */}
                {!showArchived && (
                    <div className="flex items-center mx-4 ">
                        <button
                            type="button"
                            className={`text-sm underline ${
                                selectAllPages
                                    ? "text-red-600"
                                    : "text-blue-600"
                            } mr-2`}
                            onClick={handleSelectAllPages}
                        >
                            {selectAllPages
                                ? "Deselect All Panels"
                                : "Select All Panels"}
                        </button>
                        {selectedPanels.length > 0 && (
                            <span className="text-sm text-gray-600">
                                {selectedPanels.length} of {totalActivePanels}{" "}
                                panels selected
                            </span>
                        )}
                    </div>
                )}

                {/* Table */}
                <table className="w-full border-collapse border-t border-b border-gray-300 mt-3">
                    <thead className="bg-gray-200">
                        <tr>
                            {!showArchived && (
                                <th className="px-4 py-2 border-b border-gray-300 w-12">
                                    <input
                                        type="checkbox"
                                        checked={selectAll}
                                        onChange={handleSelectAllOnPage}
                                        className="h-4 w-4 cursor-pointer"
                                        title="Select all on this page"
                                    />
                                </th>
                            )}
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
                            <th className="px-4 py-2 text-left border-b border-gray-300">
                                Coordinator
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                Supervisor
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                PSM2 Panel
                            </th>
                            <th className="px-4 py-2 border-b border-gray-300">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {paginatedPanels.map((panel, index) => (
                            <React.Fragment key={panel.id}>
                                <tr
                                    className={`text-center bg-white hover:bg-gray-100 border-b border-gray-300 ${
                                        selectedPanels.includes(panel.id)
                                            ? "bg-blue-50"
                                            : ""
                                    }`}
                                >
                                    {!showArchived && (
                                        <td
                                            className="px-4 py-2"
                                            onClick={(e) => e.stopPropagation()}
                                        >
                                            <input
                                                title="select this panels"
                                                type="checkbox"
                                                checked={selectedPanels.includes(
                                                    panel.id
                                                )}
                                                onChange={() =>
                                                    handleSelectRow(panel.id)
                                                }
                                                className="h-4 w-4 cursor-pointer"
                                            />
                                        </td>
                                    )}
                                    <td
                                        className="px-4 py-2 cursor-pointer"
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === panel.id
                                                    ? null
                                                    : panel.id
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
                                                expandedRow === panel.id
                                                    ? null
                                                    : panel.id
                                            )
                                        }
                                    >
                                        {panel.matricNo}
                                    </td>
                                    <td
                                        className="px-4 py-2 cursor-pointer text-left"
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === panel.id
                                                    ? null
                                                    : panel.id
                                            )
                                        }
                                    >
                                        {panel.name}
                                    </td>
                                    <td
                                        className="px-4 py-2 text-left cursor-pointer"
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === panel.id
                                                    ? null
                                                    : panel.id
                                            )
                                        }
                                    >
                                        {panel.username}
                                    </td>
                                    <td
                                        className="px-4 py-2 cursor-pointer "
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === panel.id
                                                    ? null
                                                    : panel.id
                                            )
                                        }
                                    >
                                        {panel.role === 1 ? (
                                            <div className="flex items-center justify-center">
                                                {panel.role && (
                                                    <Check
                                                        size={20}
                                                        className="text-green-600"
                                                    />
                                                )}
                                            </div>
                                        ) : (
                                            <div className="flex items-center justify-center">
                                                <X
                                                    size={20}
                                                    className="text-green-600"
                                                />
                                            </div>
                                        )}
                                    </td>
                                    <td
                                        className="px-4 py-2 cursor-pointer "
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === panel.id
                                                    ? null
                                                    : panel.id
                                            )
                                        }
                                    >
                                        {panel.isSupervisorPSM2 ? (
                                            <div className="flex items-center justify-center">
                                                {panel.isSupervisorPSM2 && (
                                                    <Check
                                                        size={20}
                                                        className="text-green-600"
                                                    />
                                                )}
                                            </div>
                                        ) : (
                                            <div className="flex items-center justify-center">
                                                <X
                                                    size={20}
                                                    className="text-green-600"
                                                />
                                            </div>
                                        )}
                                    </td>
                                    <td
                                        className="px-4 py-2 cursor-pointer "
                                        onClick={() =>
                                            setExpandedRow(
                                                expandedRow === panel.id
                                                    ? null
                                                    : panel.id
                                            )
                                        }
                                    >
                                        {panel.isPanelPSM2 ? (
                                            <div className="flex items-center justify-center">
                                                {panel.isPanelPSM2 && (
                                                    <Check
                                                        size={20}
                                                        className="text-green-600"
                                                    />
                                                )}
                                            </div>
                                        ) : (
                                            <div className="flex items-center justify-center">
                                                <X
                                                    size={20}
                                                    className="text-green-600"
                                                />
                                            </div>
                                        )}
                                    </td>
                                    <td className="px-4 py-2">
                                        <div className="flex items-center justify-center space-x-2">
                                            <button
                                                type="button"
                                                className="p-1 text-blue-600 hover:text-blue-800 transition"
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    setSelectedPanel(panel);
                                                    setIsEditModalOpen(true);
                                                }}
                                                title="Edit Panels"
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
                                                        className="p-1 text-gray-600 hover:text-gray-800 transition"
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            handleArchive(
                                                                panel.id
                                                            );
                                                        }}
                                                        title="Archive Panels"
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
                                                                panel.id
                                                            );
                                                        }}
                                                        title="Restore Panels"
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
                                                                panel.id
                                                            );
                                                        }}
                                                        title="Delete Panels"
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
                                {expandedRow === panel.id && (
                                    <tr className="bg-gray-50 border-b border-gray-300">
                                        <td
                                            colSpan={showArchived ? 8 : 9}
                                            className="px-4 py-2 text-left"
                                        >
                                            <p className="my-1">
                                                <strong> Email: </strong>{" "}
                                                {panel.email}
                                            </p>

                                            <div className="grid grid-cols-2 gap-4">
                                                <div>
                                                    <strong>
                                                        Students as Supervisor:
                                                    </strong>
                                                    {panel.students_sv_names
                                                        ?.length ? (
                                                        <ul className="list-disc pl-5 mt-1">
                                                            {panel.students_sv_names.map(
                                                                (
                                                                    student,
                                                                    i
                                                                ) => (
                                                                    <li
                                                                        key={`sv-${i}`}
                                                                    >
                                                                        {
                                                                            student
                                                                        }
                                                                    </li>
                                                                )
                                                            )}
                                                        </ul>
                                                    ) : (
                                                        <p className="text-gray-500 italic ml-2 mt-1">
                                                            None assigned
                                                        </p>
                                                    )}

                                                    <strong className="mt-3 block">
                                                        Students as PSM2 Panel
                                                        1:
                                                    </strong>
                                                    {panel.students_panel1_names
                                                        ?.length ? (
                                                        <ul className="list-disc pl-5 mt-1">
                                                            {panel.students_panel1_names.map(
                                                                (
                                                                    student,
                                                                    i
                                                                ) => (
                                                                    <li
                                                                        key={`panel1-${i}`}
                                                                    >
                                                                        {
                                                                            student
                                                                        }
                                                                    </li>
                                                                )
                                                            )}
                                                        </ul>
                                                    ) : (
                                                        <p className="text-gray-500 italic ml-2 mt-1">
                                                            None assigned
                                                        </p>
                                                    )}
                                                </div>

                                                <div>
                                                    <strong className="mt-3 block">
                                                        Students as PSM2 Panel
                                                        2:
                                                    </strong>
                                                    {panel.students_panel2_names
                                                        ?.length ? (
                                                        <ul className="list-disc pl-5 mt-1">
                                                            {panel.students_panel2_names.map(
                                                                (
                                                                    student,
                                                                    i
                                                                ) => (
                                                                    <li
                                                                        key={`panel2-${i}`}
                                                                    >
                                                                        {
                                                                            student
                                                                        }
                                                                    </li>
                                                                )
                                                            )}
                                                        </ul>
                                                    ) : (
                                                        <p className="text-gray-500 italic ml-2 mt-1">
                                                            None assigned
                                                        </p>
                                                    )}
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

            <AddPanelModal
                isOpen={isAddModalOpen}
                onClose={() => setIsAddModalOpen(false)}
                panelType={panelType}
            />

            <EditPanelModal
                isOpen={isEditModalOpen}
                onClose={() => {
                    setIsEditModalOpen(false);
                    setSelectedPanel(null);
                }}
                panel={selectedPanel}
                panelType={panelType}
            />
            <ImportPanelsModal
                isOpen={isImportModalOpen}
                onClose={() => setIsImoprtModalOpen(false)}
            />

            <ImportErrorModal
                isOpen={isImportErrorModalOpen}
                onClose={() => setIsImportErrorModalOpen(false)}
                message={props.flash?.warning}
            />
        </div>
    );
}
