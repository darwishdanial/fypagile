import React, { useState, useEffect } from "react";
import { X } from "lucide-react";
import { route } from "ziggy-js";
import axios from "axios";

interface Student {
    id: number;
    name: string;
    title: string;
    project_area: string;
    project_type: string;
    panelProposalId: number | null;
    panelProposal2Id: number | null;
    panelId: number | null;
    panel2Id: number | null;
}

interface AssignPanelModalProps {
    isOpen: boolean;
    onClose: () => void;
    panelId: number;
    panelType: string;
}

const AssignPanelModal: React.FC<AssignPanelModalProps> = ({
    isOpen,
    onClose,
    panelId,
    panelType,
}) => {
    useEffect(() => {
        if (isOpen) {
            document.body.style.overflow = "hidden";
        }
        return () => {
            document.body.style.overflow = "unset";
        };
    }, [isOpen]);

    const [students, setStudents] = useState<Student[]>([]);
    const [loading, setLoading] = useState(false);
    const [rowsPerPage, setRowsPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState("");
    const [expandedRow, setExpandedRow] = useState<number | null>(null);

    useEffect(() => {
        if (isOpen && panelId) {
            fetchStudents();
        }
    }, [isOpen, panelId]);

    const fetchStudents = () => {
        let route_path = "";
        let panelTypeValue = 1;
        if (panelType === "PSM1ProposalPanel1") {
            route_path = "coordinator.PSM1.panelProposal.studentList";
            panelTypeValue = 1;
        } else if (panelType === "PSM1ProposalPanel2") {
            route_path = "coordinator.PSM1.panelProposal.studentList";
            panelTypeValue = 2;
        } else if (panelType === "PSM1Panel1") {
            route_path = "coordinator.PSM1.panel.studentList";
            panelTypeValue = 1;
        } else if (panelType === "PSM1Panel2") {
            route_path = "coordinator.PSM1.panel.studentList";
            panelTypeValue = 2;
        } else if (panelType === "PSM2Panel1") {
            route_path = "coordinator.PSM2.panel.studentList";
            panelTypeValue = 1;
        } else if (panelType === "PSM2Panel2") {
            route_path = "coordinator.PSM2.panel.studentList";
            panelTypeValue = 2;
        }

        setLoading(true);

        axios
            .get(route(route_path, { panelId, panelTypeValue }))
            .then((response) => {
                setStudents(response.data);
            })
            .catch((error) => {
                console.error("Error fetching students:", error);
            })
            .finally(() => {
                setLoading(false);
            });
    };

    const handleAssign = (studentId: number, panelId: number) => {
        let route_path = "";
        if (panelType === "PSM1ProposalPanel1") {
            route_path = "coordinator.PSM1.panelProposal1.assign";
        } else if (panelType === "PSM1ProposalPanel2") {
            route_path = "coordinator.PSM1.panelProposal2.assign";
        } else if (panelType === "PSM1Panel1") {
            route_path = "coordinator.PSM1.panel1.assign";
        } else if (panelType === "PSM1Panel2") {
            route_path = "coordinator.PSM1.panel2.assign";
        } else if (panelType === "PSM2Panel1") {
            route_path = "coordinator.PSM2.panel1.assign";
        } else if (panelType === "PSM2Panel2") {
            route_path = "coordinator.PSM2.panel2.assign";
        }

        // console.log(studentId, panelId, route_path);

        axios
            .post(
                route(route_path, {
                    studentId,
                    panelId,
                })
            )
            .then(() => {
                //fetchStudents();
                setStudents((prevStudents) =>
                    prevStudents.map((student) =>{
                        if (student.id === studentId) {
                            if (panelType === "PSM1ProposalPanel1") {
                                return { ...student, panelProposalId: panelId};
                            }
                            if (panelType === "PSM1ProposalPanel2") {
                                return { ...student, panelProposal2Id: panelId};
                            }
                            if (panelType === "PSM1Panel1" || panelType === "PSM2Panel1") {
                                return { ...student, panelId, assigned: true };
                            }
                            if (panelType === "PSM1Panel2" || panelType === "PSM2Panel2") {
                                return { ...student, panel2Id: panelId };
                            }
                        }
                        return student;
                        }
                    )
                );
            })
            .catch((error) => {
                console.error("Error assigning supervisor:", error);
            });
    };

    const handleUnassign = (studentId: number) => {
        let route_path = "";
        if (panelType === "PSM1ProposalPanel1") {
            route_path = "coordinator.PSM1.panelProposal1.unassign";
        } else if (panelType === "PSM1ProposalPanel2") {
            route_path = "coordinator.PSM1.panelProposal2.unassign";
        } else if (panelType === "PSM1Panel1") {
            route_path = "coordinator.PSM1.panel1.unassign";
        } else if (panelType === "PSM1Panel2") {
            route_path = "coordinator.PSM1.panel2.unassign";
        } else if (panelType === "PSM2Panel1") {
            route_path = "coordinator.PSM2.panel1.unassign";
        } else if (panelType === "PSM2Panel2") {
            route_path = "coordinator.PSM2.panel2.unassign";
        }

        axios
            .post(route(route_path, studentId))
            .then(() => {
                //fetchStudents();
                setStudents((prevStudents) =>
                    prevStudents.map((student) =>
                        {
                            if (student.id === studentId) {
                                if (panelType === "PSM1ProposalPanel1") {
                                    return { ...student, panelProposalId: null};
                                }
                                if (panelType === "PSM1ProposalPanel2") {
                                    return { ...student, panelProposal2Id: null};
                                }
                                if (panelType === "PSM1Panel1" || panelType === "PSM2Panel1") {
                                    return { ...student, panelId: null};
                                }
                                if (panelType === "PSM1Panel2" || panelType === "PSM2Panel2") {
                                    return { ...student, panel2Id: null};
                                }
                            }
                            return student;
                            }
                    )
                );
            })
            .catch((error) => {
                console.error("Error unassigning supervisor:", error);
            });
    };

    if (!isOpen) return null;

    const filteredStudents = students.filter((student) =>
        student.name.toLowerCase().includes(searchQuery.toLowerCase())
    );

    const totalPages = Math.ceil(filteredStudents.length / rowsPerPage);
    const paginatedStudents = filteredStudents.slice(
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
                    <h2 className="text-lg font-semibold">Assign Panel</h2>
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

                    {loading ? (
                        <p className="text-center">Loading students...</p>
                    ) : (
                        <div className="max-h-[350px] xl:max-h-[700px] overflow-y-auto">
                            <table className="w-full border-collapse border-t border-b border-gray-300 mt-3">
                                <thead className="bg-gray-200">
                                    <tr>
                                        <th className="px-4 py-2 border-b border-gray-300">
                                            No
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
                                    {paginatedStudents.map((student, index) => (
                                        <React.Fragment key={student.id}>
                                            <tr
                                                className="text-center bg-white hover:bg-gray-100 border-b border-gray-300 cursor-pointer"
                                                onClick={() =>
                                                    setExpandedRow(
                                                        expandedRow ===
                                                            student.id
                                                            ? null
                                                            : student.id
                                                    )
                                                }
                                            >
                                                <td className="px-4 py-2">
                                                    {index +
                                                        1 +
                                                        (currentPage - 1) *
                                                            rowsPerPage}
                                                </td>
                                                <td className="px-4 py-2 text-left max-w-[200px]">
                                                    {student.name}
                                                </td>
                                                <td className="px-4 py-2">
                                                    {(panelType === "PSM1ProposalPanel1" && student.panelProposalId === null) ||
                                                    (panelType === "PSM1ProposalPanel2" && student.panelProposal2Id === null) ||
                                                    (["PSM1Panel1", "PSM2Panel1"].includes(panelType) && student.panelId === null) ||
                                                    (["PSM1Panel2", "PSM2Panel2"].includes(panelType) && student.panel2Id === null)
                                                             ? (
                                                        <button
                                                            type="button"
                                                            className="px-3 py-1 bg-blue-400 text-white rounded hover:bg-blue-500 transition"
                                                            onClick={(e) => {
                                                                e.stopPropagation();
                                                                handleAssign(
                                                                    student.id,
                                                                    panelId
                                                                );
                                                            }}
                                                        >
                                                            Assign
                                                        </button>
                                                    ) : (
                                                        <button
                                                            type="button"
                                                            className="px-3 py-1 bg-red-400 text-white rounded hover:bg-red-500 transition"
                                                            onClick={(e) => {
                                                                e.stopPropagation();
                                                                handleUnassign(
                                                                    student.id
                                                                );
                                                            }}
                                                        >
                                                            Unassign
                                                        </button>
                                                    )}
                                                </td>
                                            </tr>
                                            {expandedRow === student.id && (
                                                <tr className="bg-gray-100">
                                                    <td
                                                        colSpan={3}
                                                        className="px-4 py-2 text-left"
                                                    >
                                                        <p>
                                                            <strong>
                                                                Title:
                                                            </strong>{" "}
                                                            {student.title}
                                                        </p>
                                                        <p>
                                                            <strong>
                                                                Project Area:
                                                            </strong>{" "}
                                                            {
                                                                student.project_area
                                                            }
                                                        </p>
                                                        <p>
                                                            <strong>
                                                                Project Type:
                                                            </strong>{" "}
                                                            {
                                                                student.project_type
                                                            }
                                                        </p>
                                                    </td>
                                                </tr>
                                            )}
                                        </React.Fragment>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
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

export default AssignPanelModal;
