import React, { useState, useEffect } from "react";
import { usePage, router } from "@inertiajs/react";
import {
    Pencil,
    Archive,
    ArchiveRestore,
    Trash,
    FileDown,
    CirclePlus,
    ChevronDown,
    ChevronUp,
} from "lucide-react";
import { route } from "ziggy-js";
import GradeRubricModal from "../../../Components/GradeRubricModal";
import EditStudentModal from "../../../Components/EditStudentModal";
import ImportStudentModal from "../../../Components/ImportStudentModal";
import ImportErrorModal from "../../../Components/ImportErrorModal";

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
    score: Score[] | null;
}

interface Score {
    id: number;
    mark: number;
    comment: string;
    panel_name: string;
    rubric: Rubric | null;
}

interface Rubric {
    id: number;
    name: string;
    total_weight: number;
    roleType: number;
    psmType: string;
    isEnable: boolean;
    isSupervisorPSM1: boolean;
    isPanelPSM1: boolean;
    isArchivePSM1: boolean;
    isSupervisorPSM2: boolean;
    isPanelPSM2: boolean;
    progress: string;
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

export default function ViewResult() {
    const { props } = usePage<{
        students: Student[];
        rubricDevelopment: Rubric[];
        rubricResearch: Rubric[];
        flash?: Flash;
        id: number;
    }>();

    const students = props.students;
    const rubricDevelopment = props.rubricDevelopment;
    const rubricResearch = props.rubricResearch;
    const studentType = "PSM1";
    const panelId = props.id;

    const [showrubricsResearch, setShowrubricsResearch] = useState(false);
    const [expandedRow, setExpandedRow] = useState<number | null>(null);
    const [isAddModalOpen, setIsAddModalOpen] = useState(false);
    const [isEditModalOpen, setIsEditModalOpen] = useState(false);
    const [isImportErrorModalOpen, setIsImportErrorModalOpen] = useState(false);
    const [selectedStudent, setSelectedStudent] = useState<number | null>(null);
    const [isImportModalOpen, setIsImoprtModalOpen] = useState(false);

    // State for selected students
    const [selectedStudents, setSelectedStudents] = useState<number[]>([]);
    const [isGradeModalOpen, setIsGradeModalOpen] = useState(false);
    const [selectedRubric, setSelectedRubric] = useState<Rubric | null>(null);

    // State for pagination & search
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    // Filter students based on search query
    const filteredStudents = students.filter(
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

        if (props.flash?.warning) {
            setIsImportErrorModalOpen(true);
        }

        if (props.flash?.success || props.flash?.error) {
            const timer = setTimeout(() => setFlashMessage(null), 3000); // Hide after 3s
            return () => clearTimeout(timer);
        }
    }, [props.flash]); // Run effect when flash message changes

    const currentRubric = showrubricsResearch
        ? rubricResearch
        : rubricDevelopment;

    // Calculate selection statistics
    const totalActiveStudents = filteredStudents.length;
    const selectedCount = selectedStudents.length;

    // Calculate average score for a specific rubric for a student
    const calculateRubricAverage = (student: Student, rubricId: number) => {
        if (!student.score || student.score.length === 0) return 0;

        const scores = student.score.filter((s) => s.rubric?.id === rubricId);

        if (scores.length === 0) return 0;

        const sum = scores.reduce(
            (total, score) => total + Number(score.mark),
            0
        );
        return sum / scores.length;
    };

    // Calculate total score as simple sum of all rubric averages
    const calculateTotalScore = (student: Student) => {
        if (!student.score || student.score.length === 0) return "N/A";

        let totalScore = 0;
        let hasAnyScores = false;

        const applicableRubrics =
            student.project_type === "Research Based"
                ? rubricResearch
                : rubricDevelopment;

        // Calculate scores using the appropriate rubric set
        applicableRubrics.forEach((rubric) => {
            const averageScore = calculateRubricAverage(student, rubric.id);
            if (averageScore > 0) {
                totalScore += averageScore;
                hasAnyScores = true;
            }
        });

        // If no scores were found
        if (!hasAnyScores) return "N/A";

        // Return the sum of averages, formatted to 1 decimal place
        return totalScore.toFixed(1);
    };

    const handleDeleteScore = (scoreId: number) => {
        if (!window.confirm("Are you sure you want to delete this score?"))
            return;

        router.delete(route("coordinator.PSM1.score.delete", scoreId), {
            preserveScroll: true,
        });
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
                            <th className="px-4 py-2 text-center border-b border-gray-300">
                                Total Score
                            </th>
                            <th className="px-4 py-2 text-center border-b border-gray-300">
                                Details
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
                                    <td className="px-4 py-2">
                                        {calculateTotalScore(student)}
                                    </td>
                                    <td className="px-4 py-2">
                                        <button
                                            type="button"
                                            className="p-1 text-blue-600 hover:text-blue-800 transition"
                                            onClick={() => {
                                                setExpandedRow(
                                                    expandedRow === student.id
                                                        ? null
                                                        : student.id
                                                );

                                                //setShowrubricsResearch true if project_type === Research Based else false

                                                setShowrubricsResearch(
                                                    student.project_type ===
                                                        "Research Based"
                                                );
                                            }}
                                        >
                                            {expandedRow === student.id ? (
                                                <ChevronUp
                                                    size={20}
                                                    className="transition-transform duration-200 hover:scale-125"
                                                />
                                            ) : (
                                                <ChevronDown
                                                    size={20}
                                                    className="transition-transform duration-200 hover:scale-125"
                                                />
                                            )}
                                        </button>
                                    </td>
                                </tr>
                                {expandedRow === student.id && (
                                    <tr className="bg-gray-50 border-b border-gray-300">
                                        <td colSpan={6} className="px-6 py-4">
                                            <div className="text-left mb-2 font-medium text-lg border-b pb-2">
                                                Rubrics Information
                                            </div>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                {currentRubric.map((rubric) => {
                                                    const scores =
                                                        student.score?.filter(
                                                            (s) =>
                                                                s.rubric?.id ===
                                                                rubric.id
                                                        ) || [];

                                                    const averageScore =
                                                        calculateRubricAverage(
                                                            student,
                                                            rubric.id
                                                        );
                                                    const hasScores =
                                                        scores.length > 0;

                                                    return (
                                                        <div
                                                            key={`${student.id}-${rubric.id}`}
                                                            className="bg-white p-4 rounded shadow-sm border border-gray-200"
                                                        >
                                                            <div className="font-medium mb-2">
                                                                {Boolean(
                                                                    rubric.roleType ===
                                                                        1
                                                                ) && (
                                                                    <span className="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-2">
                                                                        Coordinator
                                                                    </span>
                                                                )}
                                                                {Boolean(
                                                                    rubric.roleType ===
                                                                        3
                                                                ) && (
                                                                    <span className="bg-green-100 text-green-800 px-2 py-1 rounded text-xs mr-2">
                                                                        Supervisor
                                                                    </span>
                                                                )}
                                                                {Boolean(
                                                                    rubric.roleType ===
                                                                        2
                                                                ) && (
                                                                    <span className="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs mr-2">
                                                                        Panel
                                                                    </span>
                                                                )}
                                                                <span>
                                                                    {
                                                                        rubric.name
                                                                    }
                                                                </span>
                                                                <span className="text-sm text-gray-500 ml-2">
                                                                    (
                                                                    {
                                                                        rubric.total_weight
                                                                    }
                                                                    %)
                                                                </span>
                                                            </div>

                                                            {/* Average Score Display */}
                                                            {hasScores && (
                                                                <div className="mb-3 bg-yellow-50 p-2 rounded border border-yellow-200">
                                                                    <span className="font-medium">
                                                                        Average
                                                                        Score:{" "}
                                                                    </span>
                                                                    <span className="text-lg font-semibold">
                                                                        {averageScore.toFixed(
                                                                            1
                                                                        )}
                                                                        /
                                                                        {
                                                                            rubric.total_weight
                                                                        }
                                                                    </span>
                                                                    <span className="text-sm text-gray-500 ml-1">
                                                                        (
                                                                        {
                                                                            scores.length
                                                                        }{" "}
                                                                        {scores.length ===
                                                                        1
                                                                            ? "evaluation"
                                                                            : "evaluations"}
                                                                        )
                                                                    </span>
                                                                </div>
                                                            )}

                                                            {hasScores ? (
                                                                <div>
                                                                    {scores.map(
                                                                        (
                                                                            score,
                                                                            idx
                                                                        ) => (
                                                                            <div
                                                                                key={
                                                                                    score.id
                                                                                }
                                                                                className={`${
                                                                                    idx >
                                                                                    0
                                                                                        ? "mt-4 pt-4 border-t border-gray-200"
                                                                                        : ""
                                                                                }`}
                                                                            >
                                                                                <div className="flex items-center justify-between mb-2">
                                                                                    <div className="flex items-center">
                                                                                        <span className="font-medium text-lg mr-2">
                                                                                            {
                                                                                                score.mark
                                                                                            }

                                                                                            /
                                                                                            {
                                                                                                rubric.total_weight
                                                                                            }
                                                                                        </span>

                                                                                        {score.panel_name && (
                                                                                            <span className="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                                                                                {
                                                                                                    score.panel_name
                                                                                                }
                                                                                            </span>
                                                                                        )}
                                                                                    </div>

                                                                                    <div className="flex">
                                                                                        <button
                                                                                            type="button"
                                                                                            className="p-1 text-red-600 hover:text-red-800 transition"
                                                                                            onClick={(
                                                                                                e
                                                                                            ) =>
                                                                                                handleDeleteScore(
                                                                                                    score.id
                                                                                                )
                                                                                            }
                                                                                            title={`Delete ${rubric.name} grade`}
                                                                                        >
                                                                                            <Trash
                                                                                                size={
                                                                                                    20
                                                                                                }
                                                                                                className="transition-transform duration-200 hover:scale-125"
                                                                                            />
                                                                                        </button>

                                                                                        <button
                                                                                            type="button"
                                                                                            className="p-1 text-blue-600 hover:text-blue-800 transition"
                                                                                            onClick={(
                                                                                                e
                                                                                            ) => {
                                                                                                e.stopPropagation();
                                                                                                setSelectedStudent(
                                                                                                    student.id
                                                                                                );
                                                                                                setSelectedRubric(
                                                                                                    rubric
                                                                                                );
                                                                                                setIsGradeModalOpen(
                                                                                                    true
                                                                                                );
                                                                                            }}
                                                                                            title={`Edit ${rubric.name} grade`}
                                                                                        >
                                                                                            <Pencil
                                                                                                size={
                                                                                                    20
                                                                                                }
                                                                                                className="transition-transform duration-200 hover:scale-125"
                                                                                            />
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                                {score.comment && (
                                                                                    <div className="mt-1 text-sm text-gray-600">
                                                                                        <p className="font-medium">
                                                                                            Comments:
                                                                                        </p>
                                                                                        <p className="italic">
                                                                                            {
                                                                                                score.comment
                                                                                            }
                                                                                        </p>
                                                                                    </div>
                                                                                )}
                                                                            </div>
                                                                        )
                                                                    )}
                                                                </div>
                                                            ) : (
                                                                <div className="flex items-center justify-between">
                                                                    <span className="text-gray-500">
                                                                        Not
                                                                        graded
                                                                    </span>
                                                                    {/* <button
                                                                        type="button"
                                                                        className="p-1 text-blue-600 hover:text-blue-800 transition bg-blue-50 rounded"
                                                                        onClick={(
                                                                            e
                                                                        ) => {
                                                                            e.stopPropagation();
                                                                            setSelectedStudent(
                                                                                student.id
                                                                            );
                                                                            setSelectedRubric(
                                                                                rubric
                                                                            );
                                                                            setIsGradeModalOpen(
                                                                                true
                                                                            );
                                                                        }}
                                                                        title={`Add ${rubric.name} grade`}
                                                                    >
                                                                        <Pencil
                                                                            size={
                                                                                20
                                                                            }
                                                                            className="transition-transform duration-200 hover:scale-125"
                                                                        />
                                                                    </button> */}
                                                                </div>
                                                            )}
                                                        </div>
                                                    );
                                                })}
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

            {/* <AddStudentModal
                isOpen={isAddModalOpen}
                onClose={() => setIsAddModalOpen(false)}
                studentType = {studentType}
            /> */}

            <GradeRubricModal
                isOpen={isGradeModalOpen}
                onClose={() => {
                    setIsGradeModalOpen(false);
                    setSelectedRubric(null);
                }}
                rubric={selectedRubric}
                psmType="PSM1"
                studentId={selectedStudent}
                userType={1}
                panelId={panelId}
            />

            {/* <EditStudentModal
                isOpen={isEditModalOpen}
                onClose={() => {
                    setIsEditModalOpen(false);
                    setSelectedStudent(null);
                }}
                student={selectedStudent}
                studentType={studentType}
            /> */}

            <ImportStudentModal
                isOpen={isImportModalOpen}
                onClose={() => setIsImoprtModalOpen(false)}
                studentType={studentType}
            />

            <ImportErrorModal
                isOpen={isImportErrorModalOpen}
                onClose={() => setIsImportErrorModalOpen(false)}
                message={props.flash?.warning}
            />
        </div>
    );
}
