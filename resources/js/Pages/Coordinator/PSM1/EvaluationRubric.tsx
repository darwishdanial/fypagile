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

interface Rubric {
    id: number;
    name: string;
    total_weight: number;
    psmType: string;
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
    const [showRubric, setShowRubric] = useState(true);
    const [expandedRow, setExpandedRow] = useState<number | null>(null);
    const [selectedRubric, setSelectedRubric] = useState<Rubric | null>(null);
    const [selectedCriteria, setSelectedCriteria] = useState<Criteria | null>(
        null
    );
    const [selectedRubricId, setSelectedRubricId] = useState<number | null>(
        null
    );

    const calculateCurrentWeight = (criteria: Criteria[] | null) => {
        if (!criteria || criteria.length === 0) return 0;
        return criteria.reduce((sum, item) => sum + Number(item.weight), 0);
    };
    

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
            // setIsImportErrorModalOpen(true);
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

                <div className="w-full">
                    <table className="w-full border-collapse border-t border-b border-gray-300 mt-3">
                        <thead className="bg-gray-200">
                            <tr>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    No
                                </th>
                                <th className="px-4 py-2 border-b border-gray-300">
                                    Rubric ID
                                </th>
                                <th className="t px-4 py-2 border-b border-gray-300">
                                    Name
                                </th>
                                <th className="px-4 py-2  border-b border-gray-300">
                                    Total Weight
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
                            {rubrics.map((rubric, index) => (
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
                                            {rubric.id}
                                        </td>
                                        <td
                                            className="px-4 py-2 cursor-pointer text-left"
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
                                                    className="p-1 text-blue-600 hover:text-blue-800 transition"
                                                    onClick={() => {
                                                        setSelectedRubricId(
                                                            rubric.id
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
                                                    className="p-1 text-blue-600 hover:text-blue-800 transition"
                                                    onClick={(e) => {}}
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
                                                        Current weight: {calculateCurrentWeight(rubric.criteria)}/100
                                                        {calculateCurrentWeight(rubric.criteria) !== 100 && (
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
                                                                    <span className="mr-2">{i + 1}.</span>
                                                                    <span>
                                                                        {criteria.name} ({criteria.weight})
                                                                    </span>
                                                                    <button
                                                                        type="button"
                                                                        className="p-1 text-blue-600 hover:text-blue-800 transition ml-2"
                                                                        onClick={(e) => {
                                                                            e.stopPropagation();
                                                                            setSelectedCriteria(criteria);
                                                                        }}
                                                                        title="Edit Criteria"
                                                                    >
                                                                        <Pencil
                                                                            size={16}
                                                                            className="transition-transform duration-200 hover:scale-125"
                                                                        />
                                                                    </button>
                                                                    <button
                                                                        type="button"
                                                                        className="p-1 text-blue-600 hover:text-blue-800 transition"
                                                                        onClick={(e) => {
                                                                            e.stopPropagation();
                                                                            // Add your delete function here
                                                                        }}
                                                                        title="Delete Criteria"
                                                                    >
                                                                        <Trash
                                                                            size={16}
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
                </div>
            </div>

            <AddRubricModal
                isOpen={isAddRubricModalOpen}
                onClose={() => setIsAddRubricModalOpen(false)}
                psmType="PSM1"
            />

            <AddCriteriaModal
                isOpen={isAddCriteriaModalOpen}
                onClose={() => setIsAddCriteriaModalOpen(false)}
                psmType="PSM1"
                rubricID={selectedRubricId}
            />
        </div>
    );
}
