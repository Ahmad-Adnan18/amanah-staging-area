"use client";

import {
    AnimatePresence,
    motion,
    useMotionValue,
    useSpring,
    useTransform,
} from "motion/react";
import { useEffect, useMemo, useRef, useState } from "react";

const ICONS = {
    home: (props) => (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" {...props}>
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M3 12l2-2m0 0 7-7 7 7m-2-2v10a1 1 0 0 1-1 1h-3m-6 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1m-6 0h6"
            />
        </svg>
    ),
    user: (props) => (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" {...props}>
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"
            />
        </svg>
    ),
    kelas: (props) => (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" {...props}>
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="m12 14 9-5-9-5-9 5 9 5zm0 0 6.16-3.422A12.083 12.083 0 0 1 18.825 17.057 11.952 11.952 0 0 0 12 20.055a11.952 11.952 0 0 0-6.824-2.998 12.078 12.078 0 0 1 .665-6.479L12 14Zm-4 6v-7.5l4-2.222"
            />
        </svg>
    ),
    calendar: (props) => (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" {...props}>
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"
            />
        </svg>
    ),
    chart: (props) => (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" {...props}>
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"
            />
        </svg>
    ),
    menu: (props) => (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" {...props}>
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M4 6h16M4 12h16M4 18h16"
            />
        </svg>
    ),
};

const getIcon = (name) => ICONS[name] ?? ICONS.home;

function DockLabel({ label, isHovered }) {
    const [isVisible, setIsVisible] = useState(false);

    useEffect(() => {
        const unsubscribe = isHovered.on("change", (latest) => {
            setIsVisible(latest === 1);
        });
        return () => unsubscribe();
    }, [isHovered]);

    return (
        <AnimatePresence>
            {isVisible && (
                <motion.div
                    initial={{ opacity: 0, y: 0 }}
                    animate={{ opacity: 1, y: -10 }}
                    exit={{ opacity: 0, y: 0 }}
                    transition={{ duration: 0.2 }}
                    className="absolute -top-6 left-1/2 w-fit -translate-x-1/2 whitespace-pre rounded-md border border-slate-200 bg-gray-900 px-2 py-0.5 text-xs text-white"
                    role="tooltip"
                >
                    {label}
                </motion.div>
            )}
        </AnimatePresence>
    );
}

function DockIcon({ name, active, disabled }) {
    const Icon = getIcon(name);
    const colorClass = disabled
        ? "text-slate-300"
        : active
        ? "text-red-600"
        : "text-slate-600";

    return (
        <div className={`flex items-center justify-center ${colorClass}`}>
            <Icon className="h-5 w-5" />
        </div>
    );
}

function DockItem({
    item,
    mouseX,
    spring,
    distance,
    magnification,
    baseItemSize,
}) {
    const ref = useRef(null);
    const isHovered = useMotionValue(0);
    const { label, url, icon, disabled, active } = item;

    const mouseDistance = useTransform(mouseX, (value) => {
        const rect = ref.current?.getBoundingClientRect() ?? {
            x: 0,
            width: baseItemSize,
        };
        return value - rect.x - rect.width / 2;
    });

    const targetSize = useTransform(
        mouseDistance,
        [-distance, 0, distance],
        [baseItemSize, magnification, baseItemSize]
    );
    const size = useSpring(targetSize, spring);

    const handleClick = () => {
        if (disabled || !url) {
            return;
        }
        window.location.assign(url);
    };

    const baseClasses =
        "relative inline-flex items-center justify-center rounded-full border-2 shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white";
    const stateClasses = disabled
        ? "cursor-not-allowed border-slate-200 bg-slate-100"
        : active
        ? "cursor-pointer border-red-500 bg-red-50"
        : "cursor-pointer border-slate-200 bg-white";

    return (
        <motion.button
            ref={ref}
            type="button"
            style={{ width: size, height: size }}
            onHoverStart={() => isHovered.set(1)}
            onHoverEnd={() => isHovered.set(0)}
            onFocus={() => isHovered.set(1)}
            onBlur={() => isHovered.set(0)}
            onClick={handleClick}
            disabled={disabled}
            className={`${baseClasses} ${stateClasses}`}
            aria-label={label}
        >
            <DockIcon name={icon} active={active} disabled={disabled} />
            <DockLabel label={label} isHovered={isHovered} />
        </motion.button>
    );
}

export default function Dock({
    items = [],
    className = "",
    spring = { mass: 0.1, stiffness: 150, damping: 12 },
    magnification = 70,
    distance = 200,
    panelHeight = 64,
    dockHeight = 128,
    baseItemSize = 50,
}) {
    const mouseX = useMotionValue(Infinity);
    const hoverState = useMotionValue(0);

    const maxHeight = useMemo(
        () => Math.max(dockHeight, magnification + magnification / 2 + 8),
        [dockHeight, magnification]
    );
    const heightRow = useTransform(
        hoverState,
        [0, 1],
        [panelHeight, maxHeight]
    );
    const height = useSpring(heightRow, spring);

    const sanitizedItems = Array.isArray(items) ? items : [];

    return (
        <motion.div
            style={{ height }}
            className={`flex w-full items-end justify-center ${className}`}
        >
            <motion.div
                onMouseMove={({ pageX }) => {
                    hoverState.set(1);
                    mouseX.set(pageX);
                }}
                onMouseLeave={() => {
                    hoverState.set(0);
                    mouseX.set(Infinity);
                }}
                className="flex w-fit items-end gap-3 rounded-3xl border-2 border-slate-200 bg-slate-100 px-4 pb-3 shadow-lg"
                role="toolbar"
                aria-label="Navigasi aplikasi"
            >
                {sanitizedItems.map((item, index) => (
                    <DockItem
                        key={`${item.label}-${index}`}
                        item={item}
                        mouseX={mouseX}
                        spring={spring}
                        distance={distance}
                        magnification={magnification}
                        baseItemSize={baseItemSize}
                    />
                ))}
            </motion.div>
        </motion.div>
    );
}
