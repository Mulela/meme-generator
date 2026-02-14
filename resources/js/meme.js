// resources/js/meme.js

// Image actuellement chargée (objet Image JS utilisé pour dessiner sur le canvas)
let sourceImage = null;

// Récupération centralisée de tous les éléments du DOM utilisés
// Cela évite de refaire plusieurs document.getElementById()
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

// On ne lance le script que si le formulaire et le canvas existent
if (els.form && els.canvas) {

    // Contexte 2D du canvas (API graphique HTML5)
    const ctx = els.canvas.getContext("2d");

    // Variable d’état pour activer/désactiver le contour du texte
    let outlineEnabled = true;

    /**
     * Affiche un message d’état à l’utilisateur
     * type = info | error | success
     */
    const setStatus = (msg, type = "info") => {
        els.statusBox.textContent = msg;

        // Nettoyage des anciennes classes visuelles
        els.statusBox.classList.remove(
            "border-zinc-800", "bg-zinc-900/20", "text-zinc-400",
            "border-red-900/40", "bg-red-950/30", "text-red-200",
            "border-emerald-900/40", "bg-emerald-950/30", "text-emerald-200"
        );

        // Application du style selon le type
        if (type === "error") {
            els.statusBox.classList.add("border-red-900/40", "bg-red-950/30", "text-red-200");
        } else if (type === "success") {
            els.statusBox.classList.add("border-emerald-900/40", "bg-emerald-950/30", "text-emerald-200");
        } else {
            els.statusBox.classList.add("border-zinc-800", "bg-zinc-900/20", "text-zinc-400");
        }
    };

    /**
     * Active ou désactive les boutons d’action
     * (download / save)
     */
    const setEnabled = (enabled) => {
        els.downloadBtn.disabled = !enabled;
        els.saveBtn.disabled = !enabled;
    };

    /**
     * Redimensionne le canvas proportionnellement
     * pour éviter les problèmes d’alignement avec de grandes images.
     */
    const fitCanvasToImage = (img) => {

        const MAX_WIDTH = 800; // largeur max affichée

        const naturalWidth = img.naturalWidth || img.width;
        const naturalHeight = img.naturalHeight || img.height;

        let ratio = 1;

        // Si l’image est trop large → réduction proportionnelle
        if (naturalWidth > MAX_WIDTH) {
            ratio = MAX_WIDTH / naturalWidth;
        }

        els.canvas.width = naturalWidth * ratio;
        els.canvas.height = naturalHeight * ratio;
    };

    /**
     * Découpe un texte en plusieurs lignes
     * en fonction de la largeur maximale autorisée.
     */
    const wrapLines = (text, maxWidth) => {
        const words = text.split(" ").filter(Boolean);
        const lines = [];
        let line = "";

        for (const w of words) {
            const test = line ? `${line} ${w}` : w;

            // measureText permet de mesurer la largeur du texte
            if (ctx.measureText(test).width > maxWidth && line) {
                lines.push(line);
                line = w;
            } else {
                line = test;
            }
        }

        if (line) lines.push(line);
        return lines;
    };

    /**
     * Fonction principale qui dessine le mème :
     * - image
     * - texte haut
     * - texte bas
     */
    const drawMeme = () => {
        if (!sourceImage) return;

        const W = els.canvas.width;
        const H = els.canvas.height;

        // Nettoyage du canvas
        ctx.clearRect(0, 0, W, H);

        // Dessin de l’image de base
        ctx.drawImage(sourceImage, 0, 0, W, H);

        const size = parseInt(els.textSize.value, 10) || 48;
        els.textSizeLabel.textContent = String(size);

        // Configuration du style texte
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

        // Texte du haut
        const topLines = wrapLines(top, maxTextWidth);
        topLines.forEach((ln, i) => {
            const y = margin + i * (size + 8);
            if (outlineEnabled) ctx.strokeText(ln, W / 2, y);
            ctx.fillText(ln, W / 2, y);
        });

        // Texte du bas (inversé pour empiler vers le haut)
        const bottomLines = wrapLines(bottom, maxTextWidth);
        bottomLines.slice().reverse().forEach((ln, i) => {
            const y = H - margin - i * (size + 8);
            if (outlineEnabled) ctx.strokeText(ln, W / 2, y);
            ctx.fillText(ln, W / 2, y);
        });
    };

    /**
     * Charge une image depuis un fichier utilisateur
     * et prépare l’aperçu.
     */
    const loadImageFile = (file) => {
        if (!file) return;

        const okTypes = ["image/png", "image/jpeg"];

        // Vérification type
        if (!okTypes.includes(file.type)) {
            setStatus("Type invalide. Choisis un PNG ou JPG.", "error");
            return;
        }

        // Vérification taille
        if (file.size > 5 * 1024 * 1024) {
            setStatus("Fichier trop grand. Max 5MB.", "error");
            return;
        }

        const url = URL.createObjectURL(file);
        const img = new Image();

        img.onload = () => {
            sourceImage = img;
            fitCanvasToImage(img);
            drawMeme();
            setEnabled(true);
            setStatus("Aperçu prêt. Tu peux télécharger ou enregistrer.", "success");
            URL.revokeObjectURL(url);
        };

        img.onerror = () => {
            setStatus("Impossible de charger l’image. Essaie une autre.", "error");
            URL.revokeObjectURL(url);
        };

        img.src = url;
    };

    /**
     * Téléchargement du mème en PNG
     */
    const downloadPng = () => {
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

    /**
     * Envoie le mème au backend Laravel via fetch()
     * pour l’enregistrer en base + stockage.
     */
    const saveToGallery = async () => {
        if (!sourceImage) return;

        setStatus("Enregistrement...", "info");
        els.saveBtn.disabled = true;

        const csrf = els.form.querySelector('input[name="_token"]').value;

        els.canvas.toBlob(async (blob) => {
            try {
                if (!blob) throw new Error("Export PNG impossible.");

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

                const payload = await res.json();

                if (!res.ok) {
                    const msg = payload?.message || "Erreur serveur";
                    setStatus(`Échec : ${msg}`, "error");
                    els.saveBtn.disabled = false;
                    return;
                }

                if (payload?.redirect) {
                    window.location.href = payload.redirect;
                    return;
                }

                setStatus("Enregistré !", "success");

            } catch (e) {
                setStatus(`Échec : ${e.message}`, "error");
                els.saveBtn.disabled = false;
            }
        }, "image/png");
    };

    // ====== Événements utilisateur ======

    els.pickImageBtn.addEventListener("click", () => els.imageInput.click());

    els.imageInput.addEventListener("change", (e) => {
        loadImageFile(e.target.files?.[0]);
    });

    ["input", "keyup"].forEach((evt) => {
        els.topText.addEventListener(evt, drawMeme);
        els.bottomText.addEventListener(evt, drawMeme);
    });

    els.textSize.addEventListener("input", drawMeme);

    els.toggleOutline.addEventListener("click", () => {
        outlineEnabled = !outlineEnabled;
        els.outlineLabel.textContent = outlineEnabled ? "Activé" : "Désactivé";
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
        els.outlineLabel.textContent = "Activé";
        els.imageInput.value = "";
        setEnabled(false);
        setStatus("Sélectionne une image pour activer l’aperçu.", "info");
    });

    // Drag & drop
    const prevent = (e) => { e.preventDefault(); e.stopPropagation(); };
    ["dragenter", "dragover", "dragleave", "drop"]
        .forEach((ev) => els.dropZone.addEventListener(ev, prevent));

    els.dropZone.addEventListener("drop", (e) => {
        loadImageFile(e.dataTransfer?.files?.[0]);
    });

    setEnabled(false);
    setStatus("Sélectionne une image pour activer l’aperçu.", "info");
}
