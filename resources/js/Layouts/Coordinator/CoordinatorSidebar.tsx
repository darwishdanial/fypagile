import React from "react";
import { Sidebar, Menu, MenuItem } from "react-pro-sidebar";
import { Link, usePage, useForm } from "@inertiajs/react";
import {
    Users,
    PanelRightClose,
    PanelRightOpen,
    UserCheck,
    UserPlus,
    FileText,
    ClipboardList,
    ListChecks,
    LogOut,
} from "lucide-react";

interface MenuItemType {
    icon: React.ReactNode;
    label: string;
    link: string;
}

export function CoordinatorSidebar() {
    const [collapsed, setCollapsed] = React.useState(false);
    const { url } = usePage();
    const { get } = useForm();

    const handleLogout = (e: React.FormEvent) => {
        e.preventDefault();
        get("/logout");
    };

    const menuItemsPSM1: MenuItemType[] = [
        {
            icon: <Users />,
            label: "List Students",
            link: "/Coordinator/PSM1/list-students",
        },
        {
            icon: <UserCheck />,
            label: "List Panels",
            link: "/Coordinator/PSM1/list-panels",
        },
        {
            icon: <UserPlus />,
            label: "Assign Supervisor",
            link: "/Coordinator/PSM1/assign-supervisor",
        },
        {
            icon: <UserPlus />,
            label: "Assign Proposal Panel",
            link: "/Coordinator/PSM1/assign-proposal-panel",
        },
        {
            icon: <UserPlus />,
            label: "Assign PSM1 Panel",
            link: "/Coordinator/PSM1/assign-PSM1-panel",
        },
        {
            icon: <FileText />,
            label: "View Result",
            link: "/Coordinator/PSM1/view-result",
        },
        {
            icon: <ClipboardList />,
            label: "Evaluation Rubric",
            link: "/Coordinator/PSM1/evaluation-rubric",
        },
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: "/Coordinator/PSM1/grade-supervision",
        },
        {
            icon: <ListChecks />,
            label: "Grade Proposal",
            link: "/Coordinator/PSM1/grade-proposal",
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM1",
            link: "/Coordinator/PSM1/grade-PSM1",
        },
    ];

    const menuItemsPSM2: MenuItemType[] = [
        {
            icon: <Users />,
            label: "List Students",
            link: "/Coordinator/PSM2/list-students",
        },
        {
            icon: <UserCheck />,
            label: "List Panels",
            link: "/Coordinator/PSM2/list-panels",
        },
        {
            icon: <UserPlus />,
            label: "Assign PSM2 Panel",
            link: "/Coordinator/PSM2/assign-PSM2-panel",
        },
        {
            icon: <FileText />,
            label: "View Result",
            link: "/Coordinator/PSM2/view-result",
        },
        {
            icon: <ClipboardList />,
            label: "Evaluation Rubric",
            link: "/Coordinator/PSM2/evaluation-rubric",
        },
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: "/Coordinator/PSM2/grade-supervision",
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM2",
            link: "/Coordinator/PSM2/grade-PSM2",
        },
    ];

    let menuItems: MenuItemType[] = [];
    if (url === "/Coordinator/Home") {
        menuItems = [];
    } else if (url.startsWith("/Coordinator/PSM1")) {
        menuItems = menuItemsPSM1;
    } else if (url.startsWith("/Coordinator/PSM2")) {
        menuItems = menuItemsPSM2;
    }

    return (
        <div className="flex h-full !text-[#808080] bg-[#FFFFFF]">
            <Sidebar collapsed={collapsed} backgroundColor="#FFFFFF">
                <Menu>
                    <MenuItem
                        onClick={() => setCollapsed(!collapsed)}
                        icon={collapsed && <PanelRightClose />}
                    >
                        <div className="flex justify-between">
                            <span className="pl-3 ">Coordinator</span>
                            <PanelRightOpen />
                        </div>
                    </MenuItem>

                    {menuItems.map(({ icon, label, link }, index) => {
                        const isActive = url === link;
                        const iconColor = isActive
                            ? "text-[#6D2323] font-semibold"
                            : "text-[#808080]";

                        return (
                            <MenuItem
                                key={index}
                                icon={icon}
                                className={iconColor}
                                component="div"
                            >
                                <Link
                                    href={link}
                                    style={{
                                        textDecoration: "none",
                                        color: "inherit",
                                    }}
                                >
                                    {label}
                                </Link>
                            </MenuItem>
                        );
                    })}

                    <form onSubmit={handleLogout} className="w-full">
                        <MenuItem icon={<LogOut />}>
                            <button type="submit" className="w-full text-left">
                                Log Out
                            </button>
                        </MenuItem>
                    </form>
                </Menu>
            </Sidebar>
        </div>
    );
}
