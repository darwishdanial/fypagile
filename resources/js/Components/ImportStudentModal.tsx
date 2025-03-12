import React, { useEffect, useState } from "react";
import { router, useForm, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
import { X } from "lucide-react";

interface ImportStudentModalProps {
    isOpen: boolean;
    onClose: () => void;
}

const ImportStudentModal: React.FC<ImportStudentModalProps> = ({
    isOpen,
    onClose,
}) => {
    useEffect(() => {
        if (isOpen) {
            document.body.style.overflow = "hidden";
        }
        return () => {
            document.body.style.overflow = "unset";
        };
    }, [isOpen]);

    const { data, setData, processing } = useForm({
        file: null as File | null, // Allow null initially
    });

    const { errors } = usePage().props

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setData("file", e.target.files[0]);
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append("file", data.file as Blob);

        router.post(route("coordinator.PSM1.students.import"), formData, {
            onSuccess: () => {
                onClose();
                setData("file", null);
            },
        });
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 bg-black/20 flex items-start justify-center z-50 pt-2" onClick={onClose}>
            <div className="bg-white rounded-lg w-full max-w-lg xl:max-w-2xl shadow-xl" onClick={(e) => e.stopPropagation()}>
                <div className="flex justify-between items-center py-2 px-4">
                    <h2 className="text-lg font-semibold">Import Students</h2>
                    <button title="close" type="button" onClick={onClose} className="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded p-1">
                        <X size={20} />
                    </button>
                </div>
                <hr className="border-t-1 border-gray-300" />
                <div className="p-4">
                    <form onSubmit={handleSubmit} encType="multipart/form-data">
                        <label className="block mb-2 font-medium text-center">Upload File (Excel / CSV):</label>
                        <input
                            type="file"
                            name="importStudentPSM1"
                            title="import file"
                            accept=".xlsx,.csv"
                            onChange={handleFileChange}
                            className="w-full border border-gray-300 rounded p-2 "
                            required
                        />
                        {errors.file && <p className="text-red-500 mt-1">{errors.file}</p>}
                        <div className="flex justify-end mt-4">
                            <button type="button" onClick={onClose} className="mr-2 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                                Cancel
                            </button>
                            <button type="submit" disabled={processing} className="px-4 py-2 bg-[#6D2323] text-white rounded hover:bg-[#5a1d1d] transition">
                                {processing ? "Importing..." : "Import"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default ImportStudentModal;
