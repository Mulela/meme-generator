// resources/js/meme.js

let sourceImage = null;

const els = {
    form: document.getElementById("memeForm"),
    imageInput: document.getElementById("imageInput"),
    dropZone: document.getElementById("dropZone"),
    pickImageBtn: document.getElementById("pickImageBtn"),
    topText: document.getElementById("topText"),
    bottomText: document.getElementById("bottomText"),
    textSize: document.getElementById("textSize"),
    textSizeLabel: document.getElementById("textSizeLabel"),
    toggleOutline: document.getElementById("toggleOutline"),
    outlineLabel: document.getElementById("outlineLabel"),
    downloadBtn: document.getElementById("downloadBtn"),
    saveBtn: document.getElementById("saveBtn"),
    resetBtn: document.getElementById("resetBtn"),
    statusBox: document.getElementById("statusBox"),
    canvas: document.getElementById("memeCanvas"),
};

if (els.form && els.canvas) {
    const ctx = els.canvas.getContext("2d");
    let outlineEnabled = true;

    const setStatus = (msg, type = "info") => {
        els.statusBox.textContent = msg;

        // petite coloration selon type
        els.statusBox.classList.remove(
            "border-zinc-800",
            "bg-zinc-900/20",
            "text-zinc-400",
            "border-red-900/40",
            "bg-red-950/30",
            "text-red-200",
            "border-emerald-900/40",
            "bg-emerald-950/30",
            "text-emerald-200"
        );

        if (type === "error") {
            els.statusBox.classList.add("border-red-900/40", "bg-red-950/30", "text-red-200");
        } else if (type === "success") {
            els.statusBox.classList.add("border-emerald-900/40", "bg-emerald-950/30", "text-emerald-200");
        } else {
            els.statusBox.classList.add("border-zinc-800", "bg-zinc-900/20", "text-zinc-400");
        }
    };

    const setEnabled = (enabled) => {
        els.downloadBtn.disabled = !enabled;
        els.saveBtn.disabled = !enabled;
    };

    const fitCanvasToImage = (img) => {
        els.canvas.width = img.naturalWidth || img.width;
        els.canvas.height = img.naturalHeight || img.height;
    };

    const wrapLines = (text, maxWidth) => {
        const words = text.split(" ").filter(Boolean);
        const lines = [];
        let line = "";

        for (const w of words) {
            const test = line ? `${line} ${w}` : w;
            const m = ctx.measureText(test).width;
            if (m > maxWidth && line) {
                lines.push(line);
                line = w;
            } else {
                line = test;
            }
        }
        if (line) lines.push(line);
        return lines;
    };

    const drawMeme = () => {
        if (!sourceImage) return;

        const W = els.canvas.width;
        const H = els.canvas.height;

        ctx.clearRect(0, 0, W, H);
        ctx.drawImage(sourceImage, 0, 0, W, H);

        const size = parseInt(els.textSize.value, 10) || 48;
        els.textSizeLabel.textContent = String(size);

        ctx.font = `${size}px Impact, Arial Black, Arial, sans-serif`;
        ctx.textAlign = "center";
        ctx.fillStyle = "#ffffff";
        ctx.lineJoin = "round";
        ctx.strokeStyle = "#000000";
        ctx.lineWidth = Math.max(2, Math.floor(size / 10));

        const top = (els.topText.value || "").toUpperCase();
        const bottom = (els.bottomText.value || "").toUpperCase();

        const margin = Math.max(40, Math.floor(size * 1.2));
        const maxTextWidth = W * 0.92;

        const topLines = wrapLines(top, maxTextWidth);
        topLines.forEach((ln, i) => {
            const y = margin + i * (size + 8); // +8 = meilleure respiration
            if (outlineEnabled) ctx.strokeText(ln, W / 2, y);
            ctx.fillText(ln, W / 2, y);
        });


        const bottomLines = wrapLines(bottom, maxTextWidth);
        bottomLines
            .slice()
            .reverse()
            .forEach((ln, i) => {
                const y = H - margin - i * (size + 8);
                if (outlineEnabled) ctx.strokeText(ln, W / 2, y);
                ctx.fillText(ln, W / 2, y);
            });

    };

    const loadImageFile = (file) => {
        if (!file) return;

        const okTypes = ["image/png", "image/jpeg"];
        if (!okTypes.includes(file.type)) {
            setStatus("Invalid file type. Please upload a PNG or JPG.", "error");
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            setStatus("File too large. Max 5MB.", "error");
            return;
        }

        const url = URL.createObjectURL(file);
        const img = new Image();
        img.onload = () => {
            sourceImage = img;
            fitCanvasToImage(img);
            drawMeme();
            setEnabled(true);
            setStatus("Preview ready. You can download or save to gallery.", "success");
            URL.revokeObjectURL(url);
        };
        img.onerror = () => {
            setStatus("Could not load image. Try another file.", "error");
            URL.revokeObjectURL(url);
        };
        img.src = url;
    };

    const downloadPng = async () => {
        if (!sourceImage) return;

        els.canvas.toBlob((blob) => {
            if (!blob) return;
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "meme.png";
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        }, "image/png");
    };

    const readResponse = async (res) => {
        const ct = (res.headers.get("content-type") || "").toLowerCase();
        if (ct.includes("application/json")) return await res.json();
        return await res.text();
    };

    const saveToGallery = async () => {
        if (!sourceImage) return;

        setStatus("Saving...", "info");
        els.saveBtn.disabled = true;

        const csrf = els.form.querySelector('input[name="_token"]').value;

        els.canvas.toBlob(async (blob) => {
            try {
                if (!blob) throw new Error("Export failed (blob is null).");

                const fd = new FormData();
                fd.append("top_text", els.topText.value || "");
                fd.append("bottom_text", els.bottomText.value || "");
                fd.append("meme_file", blob, "meme.png");

                const res = await fetch(els.form.action, {
                    method: "POST",
                    credentials: "same-origin",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json",
                    },
                    body: fd,
                });

                const payload = await readResponse(res);

                if (!res.ok) {
                    console.error("SAVE FAILED", { status: res.status, payload });
                    // Affichage clair pour toi
                    if (typeof payload === "string") {
                        setStatus(`Save failed (${res.status}): ${payload}`, "error");
                    } else {
                        // Laravel validation 422: payload.errors
                        const msg =
                            payload?.message ||
                            (payload?.errors ? JSON.stringify(payload.errors) : JSON.stringify(payload));
                        setStatus(`Save failed (${res.status}): ${msg}`, "error");
                    }
                    els.saveBtn.disabled = false;
                    return;
                }

                if (payload?.redirect) {
                    window.location.href = payload.redirect;
                    return;
                }

                setStatus("Saved!", "success");
            } catch (e) {
                console.error(e);
                setStatus(`Save failed: ${e.message}`, "error");
                els.saveBtn.disabled = false;
            }
        }, "image/png");
    };

    // Events
    els.pickImageBtn.addEventListener("click", () => els.imageInput.click());

    els.imageInput.addEventListener("change", (e) => {
        const file = e.target.files?.[0];
        loadImageFile(file);
    });

    ["input", "keyup"].forEach((evt) => {
        els.topText.addEventListener(evt, drawMeme);
        els.bottomText.addEventListener(evt, drawMeme);
    });

    els.textSize.addEventListener("input", drawMeme);

    els.toggleOutline.addEventListener("click", () => {
        outlineEnabled = !outlineEnabled;
        els.outlineLabel.textContent = outlineEnabled ? "Enabled" : "Disabled";
        drawMeme();
    });

    els.downloadBtn.addEventListener("click", downloadPng);
    els.saveBtn.addEventListener("click", saveToGallery);

    els.resetBtn.addEventListener("click", () => {
        sourceImage = null;
        ctx.clearRect(0, 0, els.canvas.width, els.canvas.height);
        els.topText.value = "";
        els.bottomText.value = "";
        els.textSize.value = "48";
        els.textSizeLabel.textContent = "48";
        outlineEnabled = true;
        els.outlineLabel.textContent = "Enabled";
        els.imageInput.value = "";
        setEnabled(false);
        setStatus("Select an image to enable preview.", "info");
    });

    // Drag & drop
    const prevent = (e) => { e.preventDefault(); e.stopPropagation(); };

    ["dragenter", "dragover", "dragleave", "drop"].forEach((ev) => {
        els.dropZone.addEventListener(ev, prevent);
    });

    els.dropZone.addEventListener("drop", (e) => {
        const file = e.dataTransfer?.files?.[0];
        loadImageFile(file);
    });

    setEnabled(false);
    setStatus("Select an image to enable preview.", "info");
}
