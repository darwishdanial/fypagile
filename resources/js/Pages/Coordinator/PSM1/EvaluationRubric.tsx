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

interface Rubric {
    id: number;
    name: string;
    total_weight: number;
    psmType: string;
    isEnable: boolean;
    isSupervisorPSM1: boolean;
    isPanelPSM1: boolean;
    isArchivePSM1: boolean;
    isSupervisorPSM2: boolean;
    isPanelPSM2: boolean;
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
    const { props } = usePage<{
        rubrics: Rubric[];
        flash?: Flash;
    }>();

    const rubrics = props.rubrics ?? [];
    const panelType = "PSM1";

    const [isAddRubricModalOpen, setIsAddRubricModalOpen] = useState(false);
    const [isAddCriteriaModalOpen, setIsAddCriteriaModalOpen] = useState(false);
    const [isEditCriteriaModalOpen, setIsEditCriteriaModalOpen] =
        useState(false);
    const [isEditRubricModalOpen, setIsEditRubricModalOpen] = useState(false);
    const [showRubric, setShowRubric] = useState(true);
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

    const calculateCurrentCriteriaWeight = (criteria: Criteria[] | null) => {
        if (!criteria || criteria.length === 0) return 0;
        return criteria.reduce((sum, item) => sum + Number(item.weight), 0);
    };

    const calculateCurrentRubricWeight = (rubric: Rubric[] | null) => {
        if (!rubric || rubric.length === 0) return 0;
        return rubric.reduce((sum, item) => sum + Number(item.total_weight), 0);
    };

    const [flashMessage, setFlashMessage] = useState<{
        type: "success" | "error";
        message: string;
    } | null>(null);

    const handleDeleteRubric = (id: number) => {
        const isConfirmed = confirm(
            "Are you sure you want to delete this rubric? This action cannot be undone."
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

    const handleDeleteCriteria = (id: number) => {
        router.delete(route("coordinator.PSM1.evaluationCriteria.delete", id), {
            preserveScroll: true,
        });
    };

    useEffect(() => {
        if (props.flash?.success) {
            setFlashMessage({ type: "success", message: props.flash.success });
        }
        if (props.flash?.error) {
            setFlashMessage({ type: "error", message: props.flash.error });
        }

        if (props.flash?.warning) {
            // setIsImportErrorModalOpen(true);
        }

        if (props.flash?.success || props.flash?.error) {
            const timer = setTimeout(() => setFlashMessage(null), 3000); // Hide after 3s
            return () => clearTimeout(timer);
        }
    }, [props.flash]); // Run effect when flash message changes

    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    const filteredRubrics = rubrics.filter((rubric) =>
        rubric.name.toLowerCase().includes(searchQuery.toLowerCase())
    );

    // Calculate total pages after filtering
    const totalPages = Math.ceil(filteredRubrics.length / rowsPerPage);

    // Paginate filtered panels
    const paginatedRubrics = filteredRubrics.slice(
        (currentPage - 1) * rowsPerPage,
        currentPage * rowsPerPage
    );

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
                <div className="flex items-center justify-end">
                    <button
                        type="button"
                        className="p-2 px-3 bg-blue-400 hover:bg-blue-500 transition text-white rounded ml-2 mr-4 my-4 font-semibold"
                        onClick={() => {
                            setIsAddRubricModalOpen(true);
                        }}
                        title="Add Rubric"
                    >
                        <div className="flex">
                            <CirclePlus className="mr-2" />
                            Add Rubric
                        </div>
                    </button>
                </div>

                <div className="flex justify-between mx-4">
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

                        <div className="ml-2 mt-2">
                            <span className="ml-2 text-sm text-gray-600">
                                Current total weight:{" "}
                                {calculateCurrentRubricWeight(rubrics)}
                                /100
                                {calculateCurrentRubricWeight(rubrics) !==
                                    100 && (
                                    <span className="ml-1 text-red-500">
                                        (Unbalanced)
                                    </span>
                                )}
                            </span>
                        </div>
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

                <div className="w-full">
                    <table className="w-full border-collapse border-t border-b border-gray-300 mt-3">
                        <thead className="bg-gray-200">
                            <tr>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    No
                                </th>

                                <th className="t px-4 py-2 border-b border-gray-300">
                                    Name
                                </th>
                                <th className="px-4 py-2  border-b border-gray-300">
                                    Total Weight
                                </th>
                                <th className="px-4 py-2  border-b border-gray-300">
                                    Enable
                                </th>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    Supervisor
                                </th>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    PSM1 Panel
                                </th>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {paginatedRubrics.map((rubric, index) => (
                                <React.Fragment key={rubric.id}>
                                    <tr
                                        className={
                                            "text-center bg-white hover:bg-gray-100 border-b border-gray-300"
                                        }
                                    >
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
                                            className="px-4 py-2 cursor-pointer "
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
                                                    {rubric.isEnable && (
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
                                                    expandedRow === rubric.id
                                                        ? null
                                                        : rubric.id
                                                )
                                            }
                                        >
                                            {rubric.isSupervisorPSM1 ? (
                                                <div className="flex items-center justify-center">
                                                    {rubric.isSupervisorPSM1 && (
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
                                                    expandedRow === rubric.id
                                                        ? null
                                                        : rubric.id
                                                )
                                            }
                                        >
                                            {rubric.isPanelPSM1 ? (
                                                <div className="flex items-center justify-center">
                                                    {rubric.isPanelPSM1 && (
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
                                                    onClick={(e) => {
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
                                                <button
                                                    type="button"
                                                    className="p-1 text-red-600 hover:text-red-800 transition"
                                                    onClick={(e) => {
                                                        handleDeleteRubric(
                                                            rubric.id
                                                        );
                                                    }}
                                                    title="Delete Rubric"
                                                >
                                                    <Trash
                                                        size={20}
                                                        className="transition-transform duration-200 hover:scale-125"
                                                    />
                                                </button>
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
                                                                        onClick={(
                                                                            e
                                                                        ) => {
                                                                            e.stopPropagation();
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
                                                                        onClick={(
                                                                            e
                                                                        ) => {
                                                                            e.stopPropagation();
                                                                            handleDeleteCriteria(
                                                                                criteria.id
                                                                            );
                                                                        }}
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
