import React, { useState, useEffect } from "react";
import { X } from "lucide-react";

interface PanelHistory {
    id: number;
    project_type: string;
    project_area: string;
}

interface GroupedPanelHistory {
    count: number;
    project_area: string;
    project_type: string;
}

interface PanelHistoryModalProps {
    isOpen: boolean;
    onClose: () => void;
    panelHistory: PanelHistory[] | null;
    panelName: string;
}

const PanelHistoryModal: React.FC<PanelHistoryModalProps> = ({
    isOpen,
    onClose,
    panelHistory,
    panelName,
}) => {
    useEffect(() => {
        if (isOpen) {
            document.body.style.overflow = "hidden";
        }
        return () => {
            document.body.style.overflow = "unset";
        };
    }, [isOpen]);

    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");

    if (!isOpen) return null;

    // Group panel history by project_area and project_type, and count occurrences
    const groupBy = (arr: PanelHistory[]) : GroupedPanelHistory[] => {
        const grouped = arr.reduce((acc: any, curr) => {
            const key = `${curr.project_area} | ${curr.project_type}`;
            if (!acc[key]) {
                acc[key] = { count: 0, project_area: curr.project_area, project_type: curr.project_type };
            }
            acc[key].count++;
            return acc;
        }, {});

        return Object.values(grouped);
    };

    const groupedStudents = panelHistory ? groupBy(panelHistory) : [];

    // Filter based on the search query
    const filteredStudents = groupedStudents.filter(
        (student) =>
            student.project_area.toLowerCase().includes(searchQuery.toLowerCase()) ||
            student.project_type.toLowerCase().includes(searchQuery.toLowerCase())
    );

    // Sort the grouped data by count in descending order
    const sortedStudents = filteredStudents.sort((a, b) => b.count - a.count);

    const totalPages = Math.ceil(sortedStudents.length / rowsPerPage);
    const paginatedStudents = sortedStudents.slice(
        (currentPage - 1) * rowsPerPage,
        currentPage * rowsPerPage
    );

    return (
        <div
            className="fixed inset-0 bg-black/20 flex items-center justify-center z-50"
            onClick={onClose}
        >
            <div
                className="bg-white rounded-lg w-full max-w-[700px] xl:max-w-2xl shadow-xl"
                onClick={(e) => e.stopPropagation()}
            >
                <div className="flex justify-between items-center py-2 px-4">
                    <h2 className="text-lg font-semibold">Panel History ({panelName})</h2>
                    <button
                        title="close"
                        type="button"
                        onClick={onClose}
                        className="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded p-1"
                    >
                        <X size={20} />
                    </button>
                </div>

                <hr className="border-t-1 border-gray-300"></hr>

                <div className="px-4 py-2">
                    <div className="flex justify-between mb-2">
                        <label className="font-semibold">
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

                        <input
                            type="text"
                            className="border border-gray-300 rounded p-2 bg-white"
                            placeholder="Search"
                            value={searchQuery}
                            onChange={(e) => {
                                setSearchQuery(e.target.value);
                                setCurrentPage(1);
                            }}
                        />
                    </div>

                    <div className="max-h-[350px] xl:max-h-[700px] overflow-y-auto">
                        <table className="w-full border-collapse border-t border-b border-gray-300 mt-3">
                            <thead className="bg-gray-200">
                                <tr>
                                    <th className="px-4 py-2 border-b border-gray-300">No</th>
                                    <th className="text-left px-4 py-2 border-b border-gray-300">Project Area</th>
                                    <th className="px-4 py-2 border-b border-gray-300">Project Type</th>
                                    <th className="px-4 py-2 border-b border-gray-300">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                {paginatedStudents.map((student, index) => (
                                    <React.Fragment key={student.project_area + student.project_type}>
                                        <tr className="text-center bg-white hover:bg-gray-100 border-b border-gray-300 cursor-pointer">
                                            <td className="px-4 py-2">
                                                {index + 1 + (currentPage - 1) * rowsPerPage}
                                            </td>
                                            <td className="px-4 py-2 text-left max-w-[200px]">
                                                {student.project_area}
                                            </td>
                                            <td className="px-4 py-2">{student.project_type}</td>
                                            <td className="px-4 py-2">{student.count}</td>
                                        </tr>
                                    </React.Fragment>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="flex justify-center space-x-2 items-center mb-2">
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

                <hr className="border-t-1 border-gray-300"></hr>

                <div className="flex justify-end mx-2 my-3">
                    <button
                        type="button"
                        onClick={onClose}
                        className="mr-2 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    );
};

export default PanelHistoryModal;
