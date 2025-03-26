import React, { useState, useEffect } from "react";
import {
    Pencil,
    Archive,
    ArchiveRestore,
    Trash,
    FileDown,
    Check,
    X,
    CirclePlus,
    Dot,
} from "lucide-react";
import { usePage, router } from "@inertiajs/react";
import AddRubricModal from "../../../Components/AddRubricModal";
import AddCriteriaModal from "../../../Components/AddCriteriaModal";
import EditCriteriaModal from "../../../Components/EditCriteriaModal";
import EditRubricModal from "../../../Components/EditRubricModal";
import { route } from "ziggy-js";

// Interfaces
interface Rubric {
    id: number;
    name: string;
    total_weight: number;
    psmType: string;
    isEnable: boolean;
    isResearch: boolean;
    isDevelopment: boolean;
    isCoordinatorPSM1: boolean;
    isSupervisorPSM1: boolean;
    isPanelPSM1: boolean;
    isArchivePSM1: boolean;
    isCoordinatorPSM2: boolean;
    isSupervisorPSM2: boolean;
    isPanelPSM2: boolean;
    deleted_at: Date;
    criteria: Criteria[] | null;
}

interface Criteria {
    id: number;
    rubric_id: number;
    name: string;
    weight: number;
}

interface Flash {
    error?: string;
    success?: string;
    warning?: string[][];
}

export default function EvaluationRubric() {
    // Page props from Inertia
    const { props } = usePage<{
        rubricsDevelopmentActive: Rubric[];
        rubricsDevelopmentArchive: Rubric[];
        rubricsResearchActive: Rubric[];
        rubricsResearchArchive: Rubric[];
        flash?: Flash;
    }>();

    // State variables
    const [showrubricsResearch, setShowrubricsResearch] = useState(false);
    const [isArchivedView, setIsArchivedView] = useState(false);
    const [isAddRubricModalOpen, setIsAddRubricModalOpen] = useState(false);
    const [isAddCriteriaModalOpen, setIsAddCriteriaModalOpen] = useState(false);
    const [isEditCriteriaModalOpen, setIsEditCriteriaModalOpen] =
        useState(false);
    const [isEditRubricModalOpen, setIsEditRubricModalOpen] = useState(false);
    const [expandedRow, setExpandedRow] = useState<number | null>(null);
    const [selectedRubric, setSelectedRubric] = useState<Rubric | null>(null);
    const [selectedCriteria, setSelectedCriteria] = useState<Criteria | null>(
        null
    );
    const [selectedRubricId, setSelectedRubricId] = useState<number | null>(
        null
    );
    const [selectedRubricName, setSelectedRubricName] = useState<string | null>(
        null
    );
    const [flashMessage, setFlashMessage] = useState<{
        type: "success" | "error";
        message: string;
    } | null>(null);

    // Pagination and search states
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    // Determine which rubrics to display based on current selection
    const getCurrentRubrics = () => {
        if (showrubricsResearch) {
            return isArchivedView
                ? props.rubricsResearchArchive
                : props.rubricsResearchActive;
        } else {
            return isArchivedView
                ? props.rubricsDevelopmentArchive
                : props.rubricsDevelopmentActive;
        }
    };

    // Utility functions
    const calculateCurrentCriteriaWeight = (criteria: Criteria[] | null) => {
        if (!criteria || criteria.length === 0) return 0;
        return criteria.reduce((sum, item) => sum + Number(item.weight), 0);
    };

    const calculateCurrentRubricWeight = (rubric: Rubric[] | null) => {
        if (!rubric || rubric.length === 0) return 0;
        return rubric.reduce((sum, item) => sum + Number(item.total_weight), 0);
    };

    // Handle delete actions
    const handleArchiveRubric = (id: number) => {
        router.delete(route("coordinator.PSM1.evaluationRubric.archive", id), {
            preserveScroll: true,
        });
    };

    const handleDeleteRubric = (id: number) => {
        const isConfirmed = confirm(
            "Are you sure you want to delete this rubric? The scores assosiated with the rubric will be deleted as well."
        );

        if (isConfirmed) {
            router.delete(
                route("coordinator.PSM1.evaluationRubric.delete", id),
                {
                    preserveScroll: true,
                }
            );
        }
    };

    const handleRestoreRubric = (id: number) => {
        router.post(route("coordinator.PSM1.evaluationRubric.restore", id), {
            preserveScroll: true,
        });
    };

    const handleDeleteCriteria = (id: number) => {
        router.delete(route("coordinator.PSM1.evaluationCriteria.delete", id), {
            preserveScroll: true,
        });
    };

    // Flash message effect
    useEffect(() => {
        if (props.flash?.success) {
            setFlashMessage({ type: "success", message: props.flash.success });
        }
        if (props.flash?.error) {
            setFlashMessage({ type: "error", message: props.flash.error });
        }

        if (props.flash?.success || props.flash?.error) {
            const timer = setTimeout(() => setFlashMessage(null), 3000);
            return () => clearTimeout(timer);
        }
    }, [props.flash]);

    // Filtering and pagination
    const filteredRubrics = getCurrentRubrics().filter((rubric) =>
        rubric.name.toLowerCase().includes(searchQuery.toLowerCase())
    );

    const totalPages = Math.ceil(filteredRubrics.length / rowsPerPage);

    const paginatedRubrics = filteredRubrics.slice(
        (currentPage - 1) * rowsPerPage,
        currentPage * rowsPerPage
    );

    return (
        <div className="min-h-screen bg-gray-100 flex justify-center w-full pb-6">
            {/* Flash Message */}
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
                {/* Development/Research Toggle */}
                <div className="flex items-center justify-between">
                    <div className="flex mx-4 my-4">
                        <div className="flex border border-blue-400 rounded overflow-hidden font-semibold">
                            <button
                                type="button"
                                className={`p-1 px-3 transition text-center ${
                                    !showrubricsResearch
                                        ? "bg-blue-400 hover:bg-blue-500 transition text-white"
                                        : "bg-white hover:bg-gray-100 border-r"
                                }`}
                                onClick={() => setShowrubricsResearch(false)}
                            >
                                Development
                            </button>
                            <button
                                type="button"
                                className={`p-1 px-3 transition text-center ${
                                    showrubricsResearch
                                        ? "bg-blue-400 hover:bg-blue-500 transition text-white"
                                        : "bg-white hover:bg-gray-100"
                                }`}
                                onClick={() => setShowrubricsResearch(true)}
                            >
                                Research
                            </button>
                        </div>
                    </div>

                    {/* Add Rubric Button */}
                    <div className="flex">
                        <button
                            type="button"
                            className="p-2 px-3 bg-blue-400 hover:bg-blue-500 transition text-white rounded ml-2 mr-4 my-4 font-semibold"
                            onClick={() => setIsAddRubricModalOpen(true)}
                            title="Add Rubric"
                        >
                            <div className="flex">
                                <CirclePlus className="mr-2" />
                                Add Rubric
                            </div>
                        </button>
                    </div>
                </div>

                {/* Controls and Search */}
                <div className="flex justify-between mx-4">
                    <div className="flex items-center">
                        {/* Active/Archived Toggle */}
                        <div className="pr-2">
                            <div className="flex border border-blue-400 rounded overflow-hidden font-semibold">
                                <button
                                    type="button"
                                    className={`p-1 px-3 transition text-center ${
                                        !isArchivedView
                                            ? "bg-blue-400 hover:bg-blue-500 transition text-white"
                                            : "bg-white hover:bg-gray-100 border-r"
                                    }`}
                                    onClick={() => setIsArchivedView(false)}
                                >
                                    Active
                                </button>
                                <button
                                    type="button"
                                    className={`p-1 px-3 transition text-center ${
                                        isArchivedView
                                            ? "bg-blue-400 hover:bg-blue-500 transition text-white"
                                            : "bg-white hover:bg-gray-100"
                                    }`}
                                    onClick={() => setIsArchivedView(true)}
                                >
                                    Archived
                                </button>
                            </div>
                        </div>

                        {/* Rows per Page Selector */}
                        <label className="font-semibold ml-2">
                            Rows per page:
                            <select
                                className="ml-2 border border-gray-300 rounded p-1 bg-white"
                                value={rowsPerPage}
                                onChange={(e) => {
                                    setRowsPerPage(Number(e.target.value));
                                    setCurrentPage(1);
                                }}
                            >
                                <option value={5}>5</option>
                                <option value={10}>10</option>
                                <option value={20}>20</option>
                                <option value={50}>50</option>
                            </select>
                        </label>

                        {/* Total Weight Display */}
                        <div className="ml-2 mt-2">
                            <span className="ml-2 text-sm text-gray-600">
                                Current total weight:{" "}
                                {calculateCurrentRubricWeight(
                                    getCurrentRubrics()
                                )}
                                /100
                                {calculateCurrentRubricWeight(
                                    getCurrentRubrics()
                                ) !== 100 && (
                                    <span className="ml-1 text-red-500">
                                        (Unbalanced)
                                    </span>
                                )}
                            </span>
                        </div>
                    </div>

                    {/* Search Input */}
                    <input
                        type="text"
                        className="border border-gray-300 rounded p-2 w-1/5 bg-white hover:border-[#6D2323]"
                        placeholder="Search"
                        value={searchQuery}
                        onChange={(e) => {
                            setSearchQuery(e.target.value);
                            setCurrentPage(1);
                        }}
                    />
                </div>

                {/* Rubrics Table */}
                <div className="w-full">
                    <table className="w-full border-collapse border-t border-b border-gray-300 mt-3">
                        <thead className="bg-gray-200">
                            <tr>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    No
                                </th>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    Name
                                </th>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    Total Weight
                                </th>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    Enable
                                </th>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    Role
                                </th>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {paginatedRubrics.map((rubric, index) => (
                                <React.Fragment key={rubric.id}>
                                    <tr className="text-center bg-white hover:bg-gray-100 border-b border-gray-300">
                                        <td
                                            className="px-4 py-2 cursor-pointer"
                                            onClick={() =>
                                                setExpandedRow(
                                                    expandedRow === rubric.id
                                                        ? null
                                                        : rubric.id
                                                )
                                            }
                                        >
                                            {index + 1}
                                        </td>
                                        <td
                                            className="px-4 py-2 cursor-pointer"
                                            onClick={() =>
                                                setExpandedRow(
                                                    expandedRow === rubric.id
                                                        ? null
                                                        : rubric.id
                                                )
                                            }
                                        >
                                            {rubric.name}
                                        </td>
                                        <td
                                            className="px-4 py-2 cursor-pointer"
                                            onClick={() =>
                                                setExpandedRow(
                                                    expandedRow === rubric.id
                                                        ? null
                                                        : rubric.id
                                                )
                                            }
                                        >
                                            {rubric.total_weight}
                                        </td>
                                        <td
                                            className="px-4 py-2 cursor-pointer"
                                            onClick={() =>
                                                setExpandedRow(
                                                    expandedRow === rubric.id
                                                        ? null
                                                        : rubric.id
                                                )
                                            }
                                        >
                                            {rubric.isEnable ? (
                                                <div className="flex items-center justify-center">
                                                    <Check
                                                        size={20}
                                                        className="text-green-600"
                                                    />
                                                </div>
                                            ) : (
                                                <div className="flex items-center justify-center">
                                                    <X
                                                        size={20}
                                                        className="text-red-600"
                                                    />
                                                </div>
                                            )}
                                        </td>
                                        <td
                                            className="px-4 py-2 cursor-pointer"
                                            onClick={() =>
                                                setExpandedRow(
                                                    expandedRow === rubric.id
                                                        ? null
                                                        : rubric.id
                                                )
                                            }
                                        >
                                            <div className="flex flex-col items-center text-sm">
                                                {Boolean(
                                                    rubric.isCoordinatorPSM1
                                                ) && (
                                                    <span className="py-1 px-2 bg-blue-100 text-blue-800 rounded mb-1">
                                                        Coordinator
                                                    </span>
                                                )}
                                                {Boolean(
                                                    rubric.isSupervisorPSM1
                                                ) && (
                                                    <span className="py-1 px-2 bg-green-100 text-green-800 rounded mb-1">
                                                        Supervisor
                                                    </span>
                                                )}
                                                {Boolean(
                                                    rubric.isPanelPSM1
                                                ) && (
                                                    <span className="py-1 px-2 bg-purple-100 text-purple-800 rounded mb-1">
                                                        Panel
                                                    </span>
                                                )}
                                                {!rubric.isCoordinatorPSM1 &&
                                                    !rubric.isSupervisorPSM1 &&
                                                    !rubric.isPanelPSM1 && (
                                                        <span className="py-1 px-2 bg-gray-100 text-gray-600 rounded">
                                                            None
                                                        </span>
                                                    )}
                                            </div>
                                        </td>
                                        <td className="px-4 py-2">
                                            <div className="flex items-center justify-center space-x-2">
                                                <button
                                                    type="button"
                                                    className="p-1 text-green-600 hover:text-green-800 transition"
                                                    onClick={() => {
                                                        setSelectedRubricId(
                                                            rubric.id
                                                        );
                                                        setSelectedRubricName(
                                                            rubric.name
                                                        );
                                                        setIsAddCriteriaModalOpen(
                                                            true
                                                        );
                                                    }}
                                                    title="Add Criteria"
                                                >
                                                    <CirclePlus
                                                        size={20}
                                                        className="transition-transform duration-200 hover:scale-125"
                                                    />
                                                </button>
                                                <button
                                                    type="button"
                                                    className="p-1 text-blue-600 hover:text-blue-800 transition"
                                                    onClick={() => {
                                                        setSelectedRubric(
                                                            rubric
                                                        );
                                                        setIsEditRubricModalOpen(
                                                            true
                                                        );
                                                    }}
                                                    title="Edit Rubric"
                                                >
                                                    <Pencil
                                                        size={20}
                                                        className="transition-transform duration-200 hover:scale-125"
                                                    />
                                                </button>
                                                {isArchivedView ? (
                                                    <div className="flex">
                                                        <button
                                                            type="button"
                                                            className="p-1 text-red-600 hover:text-red-800 transition"
                                                            onClick={() =>
                                                                handleRestoreRubric(
                                                                    rubric.id
                                                                )
                                                            }
                                                            title="Restore Rubric"
                                                        >
                                                            <ArchiveRestore
                                                                size={20}
                                                                className="transition-transform duration-200 hover:scale-125"
                                                            />
                                                        </button>
                                                        <button
                                                            type="button"
                                                            className="p-1 ml-2 text-red-600 hover:text-red-800 transition"
                                                            onClick={() =>
                                                                handleDeleteRubric(
                                                                    rubric.id
                                                                )
                                                            }
                                                            title="Delete Rubric"
                                                        >
                                                            <Trash
                                                                size={20}
                                                                className="transition-transform duration-200 hover:scale-125"
                                                            />
                                                        </button>
                                                    </div>
                                                ) : (
                                                    <button
                                                        type="button"
                                                        className="p-1 text-red-600 hover:text-red-800 transition"
                                                        onClick={() =>
                                                            handleArchiveRubric(
                                                                rubric.id
                                                            )
                                                        }
                                                        title="Archive rubric"
                                                    >
                                                        <Archive
                                                            size={20}
                                                            className="transition-transform duration-200 hover:scale-125"
                                                        />
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                    {expandedRow === rubric.id && (
                                        <tr className="bg-gray-50 border-b border-gray-300">
                                            <td
                                                colSpan={7}
                                                className="px-4 py-2 text-left"
                                            >
                                                <div className="mb-2">
                                                    <strong>Criteria:</strong>
                                                    <span className="ml-2 text-sm text-gray-600">
                                                        Current weight:{" "}
                                                        {calculateCurrentCriteriaWeight(
                                                            rubric.criteria
                                                        )}
                                                        /100
                                                        {calculateCurrentCriteriaWeight(
                                                            rubric.criteria
                                                        ) !== 100 && (
                                                            <span className="ml-1 text-red-500">
                                                                (Unbalanced)
                                                            </span>
                                                        )}
                                                    </span>
                                                </div>
                                                {rubric.criteria?.length ? (
                                                    <ul className="list-none pl-2 mt-1">
                                                        {rubric.criteria.map(
                                                            (criteria, i) => (
                                                                <li
                                                                    key={`criteria-${i}`}
                                                                    className="flex items-center py-1"
                                                                >
                                                                    <span className="mr-2">
                                                                        {i + 1}.
                                                                    </span>
                                                                    <span>
                                                                        {
                                                                            criteria.name
                                                                        }{" "}
                                                                        (
                                                                        {
                                                                            criteria.weight
                                                                        }
                                                                        )
                                                                    </span>
                                                                    <button
                                                                        type="button"
                                                                        className="p-1 text-blue-600 hover:text-blue-800 transition ml-2"
                                                                        onClick={() => {
                                                                            setSelectedCriteria(
                                                                                criteria
                                                                            );
                                                                            setIsEditCriteriaModalOpen(
                                                                                true
                                                                            );
                                                                        }}
                                                                        title="Edit Criteria"
                                                                    >
                                                                        <Pencil
                                                                            size={
                                                                                16
                                                                            }
                                                                            className="transition-transform duration-200 hover:scale-125"
                                                                        />
                                                                    </button>
                                                                    <button
                                                                        type="button"
                                                                        className="p-1 text-red-600 hover:text-red-800 transition"
                                                                        onClick={() =>
                                                                            handleDeleteCriteria(
                                                                                criteria.id
                                                                            )
                                                                        }
                                                                        title="Delete Criteria"
                                                                    >
                                                                        <Trash
                                                                            size={
                                                                                16
                                                                            }
                                                                            className="transition-transform duration-200 hover:scale-125"
                                                                        />
                                                                    </button>
                                                                </li>
                                                            )
                                                        )}
                                                    </ul>
                                                ) : (
                                                    <p className="text-gray-500 italic ml-2 mt-1">
                                                        None assigned
                                                    </p>
                                                )}
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

            {/* Modals */}
            <AddRubricModal
                isOpen={isAddRubricModalOpen}
                onClose={() => setIsAddRubricModalOpen(false)}
                psmType="PSM1"
            />

            <EditRubricModal
                isOpen={isEditRubricModalOpen}
                onClose={() => {
                    setIsEditRubricModalOpen(false);
                    setSelectedRubric(null);
                }}
                rubric={selectedRubric}
                psmType="PSM1"
            />

            <AddCriteriaModal
                isOpen={isAddCriteriaModalOpen}
                onClose={() => setIsAddCriteriaModalOpen(false)}
                psmType="PSM1"
                rubricID={selectedRubricId}
                rubricName={selectedRubricName}
            />

            <EditCriteriaModal
                isOpen={isEditCriteriaModalOpen}
                onClose={() => {
                    setIsEditCriteriaModalOpen(false);
                    setSelectedCriteria(null);
                }}
                criteria={selectedCriteria}
                psmType="PSM1"
            />
        </div>
    );
}
