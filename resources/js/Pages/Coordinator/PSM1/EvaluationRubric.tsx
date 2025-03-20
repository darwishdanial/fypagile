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
                <div className="flex items-center justify-between">
                    <div className="mx-4 my-4">
                        <div className="flex border border-blue-400 rounded overflow-hidden font-semibold">
                            <button
                                type="button"
                                className={`p-1 px-3 transition  text-center ${
                                    showRubric
                                        ? "bg-blue-400 hover:bg-blue-500 transition text-white"
                                        : "bg-white hover:bg-gray-100"
                                }`}
                                onClick={() => setShowRubric(true)}
                            >
                                Rubric
                            </button>
                            <button
                                type="button"
                                className={`p-1 px-3 transition text-center ${
                                    !showRubric
                                        ? "bg-blue-400 hover:bg-blue-500 transition text-white"
                                        : "bg-white hover:bg-gray-100"
                                }`}
                                onClick={() => setShowRubric(false)}
                            >
                                Criteria
                            </button>
                        </div>
                    </div>

                    <div className="flex">
                        {showRubric && (
                            <button
                                type="button"
                                className="p-2 px-3 bg-blue-400 hover:bg-blue-500 transition text-white rounded ml-2 mr-4 my-4 font-semibold"
                                onClick={() => {
                                    setIsAddRubricModalOpen(true);
                                }}
                                title="Import Students"
                            >
                                <div className="flex">
                                    <CirclePlus className="mr-2" />
                                    Add Rubric
                                </div>
                            </button>
                        )}
                        {!showRubric && (
                            <button
                                type="button"
                                className="p-2 px-3 bg-blue-400 hover:bg-blue-500 transition text-white rounded ml-2 mr-4 my-4 font-semibold"
                                onClick={() => {
                                    setIsAddCriteriaModalOpen(true);
                                }}
                                title="Import Students"
                            >
                                <div className="flex">
                                    <CirclePlus className="mr-2" />
                                    Add Criteria
                                </div>
                            </button>
                        )}
                    </div>
                </div>

                <div className="w-full">
                    {showRubric && (
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
                                                        expandedRow ===
                                                            rubric.id
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
                                                        expandedRow ===
                                                            rubric.id
                                                            ? null
                                                            : rubric.id
                                                    )
                                                }
                                            >
                                                {rubric.id}
                                            </td>
                                            <td
                                                className="px-4 py-2 cursor-pointer"
                                                onClick={() =>
                                                    setExpandedRow(
                                                        expandedRow ===
                                                            rubric.id
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
                                                        expandedRow ===
                                                            rubric.id
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
                                                        expandedRow ===
                                                            rubric.id
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
                                                        expandedRow ===
                                                            rubric.id
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
                                                        onClick={(e) => {}}
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
                                                    <strong className="mt-3 block">
                                                        Criteria:
                                                    </strong>
                                                    {rubric.criteria?.length ? (
                                                        <ul className="list-disc pl-5 mt-1">
                                                            {rubric.criteria.map(
                                                                (
                                                                    criteria,
                                                                    i
                                                                ) => (
                                                                    <li
                                                                        key={`criteria-${i}`}
                                                                    >
                                                                        {
                                                                            criteria.name
                                                                        }{" "}
                                                                        (
                                                                        {
                                                                            criteria.weight
                                                                        }
                                                                        )
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
                    )}
                    {!showRubric && (
                        <table className="w-full border-collapse border-t border-b border-gray-300 mt-3">
                            <thead className="bg-gray-200">
                                <tr>
                                    <th className="px-4 py-2 border-b border-gray-300">
                                        No
                                    </th>
                                    <th className="px-4 py-2 border-b border-gray-300">
                                        Criteria ID
                                    </th>
                                    <th className="text-left px-4 py-2 border-b border-gray-300">
                                        Name
                                    </th>
                                    <th className="px-4 py-2 text-left border-b border-gray-300">
                                        Weight
                                    </th>
                                    <th className="px-4 py-2 border-b border-gray-300">
                                        Rubric
                                    </th>
                                    <th className="px-4 py-2 border-b border-gray-300">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {rubrics
                                    .flatMap((rubric) =>
                                        (rubric.criteria || []).map(
                                            (criteria) => ({
                                                ...criteria,
                                                rubricName: rubric.name, // Include the parent rubric name
                                            })
                                        )
                                    )
                                    .map((criteria, index) => (
                                        <React.Fragment key={criteria.id}>
                                            <tr className="text-center bg-white hover:bg-gray-100 border-b border-gray-300">
                                                <td className="px-4 py-2 cursor-pointer">
                                                    {index + 1}
                                                </td>
                                                <td className="px-4 py-2 cursor-pointer">
                                                    {criteria.id}
                                                </td>
                                                <td className="px-4 py-2 cursor-pointer text-left">
                                                    {criteria.name}
                                                </td>
                                                <td className="px-4 py-2 cursor-pointer text-left">
                                                    {criteria.weight}
                                                </td>
                                                <td className="px-4 py-2 cursor-pointer">
                                                    {criteria.rubricName}
                                                </td>
                                                <td className="px-4 py-2">
                                                    <div className="flex items-center justify-center space-x-2">
                                                        <button
                                                            type="button"
                                                            className="p-1 text-blue-600 hover:text-blue-800 transition"
                                                            onClick={(e) => {}}
                                                            title="Edit Criteria"
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
                                                            title="Delete Criteria"
                                                        >
                                                            <Trash
                                                                size={20}
                                                                className="transition-transform duration-200 hover:scale-125"
                                                            />
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </React.Fragment>
                                    ))}
                            </tbody>
                        </table>
                    )}
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
            />
        </div>
    );
}
