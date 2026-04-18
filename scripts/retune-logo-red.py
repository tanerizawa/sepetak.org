#!/usr/bin/env python3
"""
Retune warna merah pada SVG logo agar selaras dengan palet web app.

Sumber (master trace dari docs/logo-sepetak.svg) memakai merah hangat
#CB3029 (hue ≈ 3°). Web app pakai flag-500 = #C8102E (hue ≈ 350°,
saturasi lebih tinggi). Script ini menggeser hue setiap fill yang
termasuk "red family" dengan delta yang sama, lalu juga menyesuaikan
saturation/lightness proporsional terhadap delta antara warna kanonik
lama dan baru. Piksel cokelat padi (hue ≈ 28°) dan kuning bintang
dilewati.

Pemakaian:
    python3 scripts/retune-logo-red.py \
        --src docs/logo-sepetak.svg \
        --dst docs/logo-sepetak-tuned.svg
"""
from __future__ import annotations

import argparse
import colorsys
import re
from pathlib import Path

SRC_RED = "#CB3029"  # merah kanonik di master logo
DST_RED = "#C8102E"  # flag-500 di tailwind.config.js

HEX_RE = re.compile(r'fill="#([0-9A-Fa-f]{6})"')


def hex_to_rgb(h: str) -> tuple[int, int, int]:
    h = h.lstrip("#")
    return int(h[0:2], 16), int(h[2:4], 16), int(h[4:6], 16)


def rgb_to_hex(r: int, g: int, b: int) -> str:
    return "#{:02X}{:02X}{:02X}".format(
        max(0, min(255, r)), max(0, min(255, g)), max(0, min(255, b))
    )


def hex_to_hls(h: str) -> tuple[float, float, float]:
    r, g, b = hex_to_rgb(h)
    return colorsys.rgb_to_hls(r / 255, g / 255, b / 255)


def hls_to_hex(h: float, l: float, s: float) -> str:
    r, g, b = colorsys.hls_to_rgb(h % 1.0, max(0.0, min(1.0, l)), max(0.0, min(1.0, s)))
    return rgb_to_hex(round(r * 255), round(g * 255), round(b * 255))


def hue_distance_to_red_deg(h_frac: float) -> float:
    deg = h_frac * 360.0
    if deg > 180:
        deg -= 360
    return abs(deg)


def is_red_family(h: float, l: float, s: float) -> bool:
    # Saturasi rendah → grayscale/warna datar, lewati.
    if s < 0.25:
        return False
    # Lightness ekstrem → hampir putih/hitam, lewati.
    if l < 0.05 or l > 0.95:
        return False
    # Hue dalam ±15° dari pure red (0°).
    return hue_distance_to_red_deg(h) <= 15.0


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--src", required=True, help="SVG sumber")
    parser.add_argument("--dst", required=True, help="SVG tujuan")
    args = parser.parse_args()

    src_path = Path(args.src)
    dst_path = Path(args.dst)

    h_src, l_src, s_src = hex_to_hls(SRC_RED)
    h_dst, l_dst, s_dst = hex_to_hls(DST_RED)

    # Hue delta (wrap-aware) dan scale S/L relatif
    dh = h_dst - h_src
    if dh > 0.5:
        dh -= 1.0
    elif dh < -0.5:
        dh += 1.0
    ds = s_dst - s_src
    dl = l_dst - l_src

    svg = src_path.read_text()

    stats = {"total": 0, "shifted": 0}

    def replace(match: re.Match[str]) -> str:
        stats["total"] += 1
        hex_ = "#" + match.group(1)
        h, l, s = hex_to_hls(hex_)
        if not is_red_family(h, l, s):
            return match.group(0)
        stats["shifted"] += 1
        new_h = h + dh
        # Skala S/L proporsional ringan (preserve anti-alias blend).
        new_s = min(1.0, max(0.0, s + ds))
        new_l = min(1.0, max(0.0, l + dl))
        return f'fill="{hls_to_hex(new_h, new_l, new_s)}"'

    new_svg = HEX_RE.sub(replace, svg)
    dst_path.write_text(new_svg)

    print(
        f"Total fill diproses : {stats['total']}\n"
        f"Fill di-shift merah : {stats['shifted']}\n"
        f"Src red             : {SRC_RED} (H={h_src*360:6.2f}° S={s_src:.3f} L={l_src:.3f})\n"
        f"Dst red             : {DST_RED} (H={h_dst*360:6.2f}° S={s_dst:.3f} L={l_dst:.3f})\n"
        f"Delta               : dH={dh*360:+.2f}°  dS={ds:+.3f}  dL={dl:+.3f}\n"
        f"Output              : {dst_path}"
    )
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
