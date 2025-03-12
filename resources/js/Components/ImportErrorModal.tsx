import React, { useEffect } from "react";
import { X } from "lucide-react";

interface ValidationError {
    row: number;
    attribute: string;
    errors: string[];
    values: Record<string, string>;
}

interface ImportErrorModalProps {
    isOpen: boolean;
    onClose: () => void;
    message?: string[][];
}

const ImportErrorModal: React.FC<ImportErrorModalProps> = ({
    isOpen,
    onClose,
    message,
}) => {
    useEffect(() => {
        if (isOpen) {
            document.body.style.overflow = "hidden";
        }
        return () => {
            document.body.style.overflow = "unset";
        };
    }, [isOpen]);

    if (!isOpen) return null;

    return (
        <div
            className="fixed inset-0 bg-black/20 flex items-center justify-center z-50"
            onClick={onClose}
        >
            <div
                className="bg-white rounded-lg w-full max-w-lg shadow-xl max-h-[80vh] overflow-auto"
                onClick={(e) => e.stopPropagation()}
            >
                <div className="flex justify-between items-center py-2 px-4">
                    <h2 className="text-lg font-semibold">Import error</h2>
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
                <div className="py-4 p-4">
                    {!message || message.length === 0 ? (
                        <p className="text-gray-700">
                            An error occurred during import.
                        </p>
                    ) : (
                        <div className="space-y-4">
                            <p className="font-medium">
                                The following errors were found in your import
                                file:
                            </p>
                            {message.map((errorArray, index) => (
                                <div
                                    key={index}
                                    className="border rounded p-2 bg-red-50"
                                >
                                    <ul className="list-disc pl-5 ">
                                        {errorArray.map((err, errIndex) => (
                                            <li
                                                key={errIndex}
                                                className="text-red-700"
                                            >
                                                {err}
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            ))}
                            <p className="mt-4">
                                Please correct these errors and try importing
                                again. Only upload the error rows.
                            </p>
                        </div>
                    )}
                </div>
                <hr className="border-t-1 border-gray-300"></hr>
                <div className="flex justify-end p-2">
                    <button
                        type="button"
                        onClick={onClose}
                        className="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    );
};

export default ImportErrorModal;
